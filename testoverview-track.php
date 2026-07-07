<?php declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

function analyticsJsonResponse(int $status, array $payload): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function analyticsString($value, int $maxLength): ?string
{
    if (!is_string($value)) {
        return null;
    }

    $value = trim((string) preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $value));
    if ($value === '') {
        return null;
    }

    return function_exists('mb_substr')
        ? mb_substr($value, 0, $maxLength, 'UTF-8')
        : substr($value, 0, $maxLength);
}

function analyticsIdentifier($value): ?string
{
    $identifier = analyticsString($value, 64);
    if ($identifier === null || !preg_match('/^[A-Za-z0-9_-]{16,64}$/', $identifier)) {
        return null;
    }
    return $identifier;
}

function analyticsUnsignedInt($value, int $maximum): ?int
{
    if (!is_int($value) && !is_float($value) && !is_string($value)) {
        return null;
    }
    if (!is_numeric($value)) {
        return null;
    }
    $number = (int) $value;
    return $number >= 0 && $number <= $maximum ? $number : null;
}

function analyticsDateTime($value): ?string
{
    if (!is_string($value) || $value === '') {
        return null;
    }
    try {
        return (new DateTimeImmutable($value))
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s.v');
    } catch (Throwable $exception) {
        return null;
    }
}

function analyticsDatabaseConfig(): array
{
    $configPath = __DIR__ . '/testoverview-analytics-config.php';
    if (is_file($configPath)) {
        $config = require $configPath;
        if (is_array($config)) {
            return $config;
        }
    }

    $environmentConfig = [
        'host' => getenv('TESTOVERVIEW_DB_HOST') ?: 'localhost',
        'port' => (int) (getenv('TESTOVERVIEW_DB_PORT') ?: 3306),
        'database' => getenv('TESTOVERVIEW_DB_NAME') ?: '',
        'username' => getenv('TESTOVERVIEW_DB_USER') ?: '',
        'password' => getenv('TESTOVERVIEW_DB_PASS') ?: '',
    ];
    if ($environmentConfig['database'] !== '' && $environmentConfig['username'] !== '') {
        return $environmentConfig;
    }

    // Reuse the existing website credentials without loading or executing index.php.
    $legacyConfigPath = __DIR__ . '/index.php';
    $legacySource = is_file($legacyConfigPath)
        ? file_get_contents($legacyConfigPath, false, null, 0, 8192)
        : false;
    if (is_string($legacySource)) {
        $constantMap = [
            'host' => 'STOCKS2_DB_HOST',
            'database' => 'STOCKS2_DB_NAME',
            'username' => 'STOCKS2_DB_USER',
            'password' => 'STOCKS2_DB_PASS',
        ];
        $legacyConfig = ['port' => 3306];
        foreach ($constantMap as $configKey => $constantName) {
            $pattern = "/define\\(\\s*'" . preg_quote($constantName, '/') . "'\\s*,\\s*'((?:\\\\.|[^'])*)'\\s*\\)\\s*;/";
            if (preg_match($pattern, $legacySource, $matches) !== 1) {
                $legacyConfig = [];
                break;
            }
            $legacyConfig[$configKey] = stripcslashes($matches[1]);
        }
        if ($legacyConfig !== []) {
            return $legacyConfig;
        }
    }

    return $environmentConfig;
}

function analyticsDatabaseConnection(): PDO
{
    $config = analyticsDatabaseConfig();
    foreach (['host', 'database', 'username'] as $requiredKey) {
        if (!isset($config[$requiredKey]) || (string) $config[$requiredKey] === '') {
            throw new RuntimeException('Analytics database configuration is incomplete.');
        }
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        (string) $config['host'],
        (int) ($config['port'] ?? 3306),
        (string) $config['database']
    );

    return new PDO($dsn, (string) $config['username'], (string) $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

function analyticsDashboardToken(): string
{
    $config = analyticsDatabaseConfig();
    $configuredToken = isset($config['dashboard_token'])
        ? trim((string) $config['dashboard_token'])
        : '';
    if ($configuredToken !== '') {
        return $configuredToken;
    }
    return trim((string) (getenv('TESTOVERVIEW_DASHBOARD_TOKEN') ?: ''));
}

function analyticsClientIpAddress(): ?string
{
    $remoteAddress = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
    return filter_var($remoteAddress, FILTER_VALIDATE_IP) !== false
        ? $remoteAddress
        : null;
}

function analyticsDashboardPeriodStart(string $period): string
{
    if ($period === 'day') {
        return 'CURRENT_DATE';
    }
    if ($period === 'month') {
        return 'DATE_SUB(CURRENT_DATE, INTERVAL 29 DAY)';
    }
    return 'DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY)';
}

function analyticsDashboardData(PDO $pdo, string $period, bool $includeIpAddresses = false): array
{
    $periodStart = analyticsDashboardPeriodStart($period);
    $applicationKey = 'test_overview';

    $periodCountsStatement = $pdo->prepare(
        'SELECT
            COALESCE(SUM(started_at >= CURRENT_DATE), 0) AS day_count,
            COALESCE(SUM(started_at >= DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY)), 0) AS week_count,
            COALESCE(SUM(started_at >= DATE_SUB(CURRENT_DATE, INTERVAL 29 DAY)), 0) AS month_count
         FROM test_overview_sessions
         WHERE application_key = :application_key'
    );
    $periodCountsStatement->execute([':application_key' => $applicationKey]);
    $periodCounts = $periodCountsStatement->fetch() ?: [];

    $summaryStatement = $pdo->prepare(
        'SELECT
            COUNT(*) AS session_count,
            COALESCE(SUM(s.device_type = "mobile"), 0) AS mobile_count,
            COALESCE(SUM(s.device_type = "tablet"), 0) AS tablet_count,
            COALESCE(SUM(s.device_type = "desktop"), 0) AS desktop_count,
            COALESCE(AVG(s.engaged_seconds), 0) AS average_duration_seconds,
            COALESCE(MAX(s.engaged_seconds), 0) AS longest_duration_seconds,
            COALESCE(SUM(s.engaged_seconds), 0) AS total_duration_seconds,
            COALESCE(SUM(e.click_count), 0) AS click_count,
            COALESCE(AVG(COALESCE(e.click_count, 0)), 0) AS average_clicks,
            COALESCE(AVG(1 + COALESCE(e.view_change_count, 0)), 0) AS average_path_length,
            COALESCE(MAX(1 + COALESCE(e.view_change_count, 0)), 0) AS longest_path_length
         FROM test_overview_sessions s
         LEFT JOIN (
            SELECT
                session_id,
                SUM(event_type = "click") AS click_count,
                SUM(event_type = "view_change") AS view_change_count
            FROM test_overview_events
            WHERE application_key = :event_application_key
            GROUP BY session_id
         ) e ON e.session_id = s.session_id
         WHERE s.application_key = :session_application_key
           AND s.started_at >= ' . $periodStart
    );
    $summaryStatement->execute([
        ':event_application_key' => $applicationKey,
        ':session_application_key' => $applicationKey,
    ]);
    $summary = $summaryStatement->fetch() ?: [];

    $topClicksStatement = $pdo->prepare(
        'SELECT
            COALESCE(NULLIF(element_key, ""), "unknown") AS element_key,
            COALESCE(NULLIF(element_label, ""), "Unlabelled control") AS element_label,
            COUNT(*) AS click_count,
            COUNT(DISTINCT session_id) AS session_count
         FROM test_overview_events
         WHERE application_key = :application_key
           AND event_type = "click"
           AND server_received_at >= ' . $periodStart . '
         GROUP BY element_key, element_label
         ORDER BY click_count DESC, element_label ASC
         LIMIT 8'
    );
    $topClicksStatement->execute([':application_key' => $applicationKey]);
    $topClicks = $topClicksStatement->fetchAll();

    $transitionsStatement = $pdo->prepare(
        'SELECT
            previous_view_key,
            target_view_key,
            COUNT(*) AS transition_count
         FROM test_overview_events
         WHERE application_key = :application_key
           AND event_type = "view_change"
           AND previous_view_key IS NOT NULL
           AND target_view_key IS NOT NULL
           AND server_received_at >= ' . $periodStart . '
         GROUP BY previous_view_key, target_view_key
         ORDER BY transition_count DESC
         LIMIT 8'
    );
    $transitionsStatement->execute([':application_key' => $applicationKey]);
    $transitions = $transitionsStatement->fetchAll();

    $audioSummaryStatement = $pdo->prepare(
        'SELECT
            COALESCE(SUM(event_type = "audio_autoplay_initial" AND audio_autoplay_enabled = 1), 0) AS initial_autoplay_on,
            COALESCE(SUM(event_type = "audio_autoplay_initial" AND audio_autoplay_enabled = 0), 0) AS initial_autoplay_off,
            COALESCE(SUM(event_type = "audio_autoplay_toggle" AND audio_autoplay_enabled = 1), 0) AS autoplay_turned_on,
            COALESCE(SUM(event_type = "audio_autoplay_toggle" AND audio_autoplay_enabled = 0), 0) AS autoplay_turned_off,
            COALESCE(SUM(event_type = "audio_start" AND audio_source = "manual"), 0) AS manual_starts,
            COALESCE(SUM(event_type = "audio_start" AND audio_source = "autoplay"), 0) AS autoplay_starts,
            COALESCE(SUM(event_type = "audio_pause"), 0) AS pauses,
            COALESCE(SUM(event_type = "audio_complete"), 0) AS completions,
            COALESCE(SUM(event_type = "audio_stop"), 0) AS stops,
            COALESCE(AVG(CASE WHEN event_type IN ("audio_complete", "audio_stop", "audio_error") THEN audio_listened_ms END), 0) AS average_listened_ms,
            COUNT(DISTINCT CASE WHEN event_type = "audio_start" THEN session_id END) AS listening_sessions
         FROM test_overview_events
         WHERE application_key = :application_key
           AND event_type LIKE "audio_%"
           AND server_received_at >= ' . $periodStart
    );
    $audioSummaryStatement->execute([':application_key' => $applicationKey]);
    $audioSummary = $audioSummaryStatement->fetch() ?: [];

    $audioPagesStatement = $pdo->prepare(
        'SELECT
            COALESCE(NULLIF(view_key, ""), "unknown") AS view_key,
            COALESCE(SUM(event_type = "audio_start"), 0) AS starts,
            COALESCE(SUM(event_type = "audio_start" AND audio_source = "manual"), 0) AS manual_starts,
            COALESCE(SUM(event_type = "audio_start" AND audio_source = "autoplay"), 0) AS autoplay_starts,
            COALESCE(SUM(event_type = "audio_complete"), 0) AS completions,
            COALESCE(SUM(event_type = "audio_stop"), 0) AS stops,
            COALESCE(AVG(CASE WHEN event_type IN ("audio_complete", "audio_stop", "audio_error") THEN audio_listened_ms END), 0) AS average_listened_ms
         FROM test_overview_events
         WHERE application_key = :application_key
           AND event_type LIKE "audio_%"
           AND server_received_at >= ' . $periodStart . '
         GROUP BY view_key
         HAVING starts > 0
         ORDER BY starts DESC, view_key ASC
         LIMIT 20'
    );
    $audioPagesStatement->execute([':application_key' => $applicationKey]);
    $audioPages = $audioPagesStatement->fetchAll();

    $ipAddressSelect = $includeIpAddresses ? 's.ip_address' : 'NULL';
    $recentSessionsStatement = $pdo->prepare(
        'SELECT
            s.session_id,
            s.started_at,
            s.last_seen_at,
            s.device_type,
            s.entry_page_path,
            s.entry_view_key,
            s.last_view_key,
            ' . $ipAddressSelect . ' AS ip_address,
            s.engaged_seconds AS duration_seconds,
            COALESCE(e.click_count, 0) AS click_count,
            1 + COALESCE(e.view_change_count, 0) AS path_length
         FROM test_overview_sessions s
         LEFT JOIN (
            SELECT
                session_id,
                SUM(event_type = "click") AS click_count,
                SUM(event_type = "view_change") AS view_change_count
            FROM test_overview_events
            WHERE application_key = :event_application_key
            GROUP BY session_id
         ) e ON e.session_id = s.session_id
         WHERE s.application_key = :session_application_key
           AND s.started_at >= ' . $periodStart . '
         ORDER BY s.started_at DESC
         LIMIT 12'
    );
    $recentSessionsStatement->execute([
        ':event_application_key' => $applicationKey,
        ':session_application_key' => $applicationKey,
    ]);
    $recentSessions = $recentSessionsStatement->fetchAll();

    $dailySessionsStatement = $pdo->prepare(
        'SELECT DATE(started_at) AS metric_date, COUNT(*) AS metric_count
         FROM test_overview_sessions
         WHERE application_key = :application_key
           AND started_at >= DATE_SUB(CURRENT_DATE, INTERVAL 29 DAY)
         GROUP BY DATE(started_at)'
    );
    $dailySessionsStatement->execute([':application_key' => $applicationKey]);
    $dailySessionCounts = [];
    foreach ($dailySessionsStatement->fetchAll() as $row) {
        $dailySessionCounts[(string) $row['metric_date']] = (int) $row['metric_count'];
    }

    $dailyClicksStatement = $pdo->prepare(
        'SELECT DATE(server_received_at) AS metric_date, COUNT(*) AS metric_count
         FROM test_overview_events
         WHERE application_key = :application_key
           AND event_type = "click"
           AND server_received_at >= DATE_SUB(CURRENT_DATE, INTERVAL 29 DAY)
         GROUP BY DATE(server_received_at)'
    );
    $dailyClicksStatement->execute([':application_key' => $applicationKey]);
    $dailyClickCounts = [];
    foreach ($dailyClicksStatement->fetchAll() as $row) {
        $dailyClickCounts[(string) $row['metric_date']] = (int) $row['metric_count'];
    }

    $timeline = [];
    $timelineStart = new DateTimeImmutable('-29 days');
    for ($dayOffset = 0; $dayOffset < 30; $dayOffset += 1) {
        $date = $timelineStart->modify('+' . $dayOffset . ' days')->format('Y-m-d');
        $timeline[] = [
            'date' => $date,
            'sessions' => $dailySessionCounts[$date] ?? 0,
            'clicks' => $dailyClickCounts[$date] ?? 0,
        ];
    }

    return [
        'period' => $period,
        'generated_at' => gmdate('c'),
        'session_counts' => [
            'day' => (int) ($periodCounts['day_count'] ?? 0),
            'week' => (int) ($periodCounts['week_count'] ?? 0),
            'month' => (int) ($periodCounts['month_count'] ?? 0),
        ],
        'summary' => [
            'sessions' => (int) ($summary['session_count'] ?? 0),
            'mobile' => (int) ($summary['mobile_count'] ?? 0),
            'tablet' => (int) ($summary['tablet_count'] ?? 0),
            'desktop' => (int) ($summary['desktop_count'] ?? 0),
            'average_duration_seconds' => (float) ($summary['average_duration_seconds'] ?? 0),
            'longest_duration_seconds' => (int) ($summary['longest_duration_seconds'] ?? 0),
            'total_duration_seconds' => (int) ($summary['total_duration_seconds'] ?? 0),
            'clicks' => (int) ($summary['click_count'] ?? 0),
            'average_clicks' => (float) ($summary['average_clicks'] ?? 0),
            'average_path_length' => (float) ($summary['average_path_length'] ?? 0),
            'longest_path_length' => (int) ($summary['longest_path_length'] ?? 0),
        ],
        'top_clicks' => array_map(static function (array $row): array {
            return [
                'element_key' => (string) $row['element_key'],
                'element_label' => (string) $row['element_label'],
                'clicks' => (int) $row['click_count'],
                'sessions' => (int) $row['session_count'],
            ];
        }, $topClicks),
        'transitions' => array_map(static function (array $row): array {
            return [
                'from' => (string) $row['previous_view_key'],
                'to' => (string) $row['target_view_key'],
                'count' => (int) $row['transition_count'],
            ];
        }, $transitions),
        'audio' => [
            'initial_autoplay_on' => (int) ($audioSummary['initial_autoplay_on'] ?? 0),
            'initial_autoplay_off' => (int) ($audioSummary['initial_autoplay_off'] ?? 0),
            'autoplay_turned_on' => (int) ($audioSummary['autoplay_turned_on'] ?? 0),
            'autoplay_turned_off' => (int) ($audioSummary['autoplay_turned_off'] ?? 0),
            'manual_starts' => (int) ($audioSummary['manual_starts'] ?? 0),
            'autoplay_starts' => (int) ($audioSummary['autoplay_starts'] ?? 0),
            'pauses' => (int) ($audioSummary['pauses'] ?? 0),
            'completions' => (int) ($audioSummary['completions'] ?? 0),
            'stops' => (int) ($audioSummary['stops'] ?? 0),
            'average_listened_seconds' => ((float) ($audioSummary['average_listened_ms'] ?? 0)) / 1000,
            'listening_sessions' => (int) ($audioSummary['listening_sessions'] ?? 0),
            'pages' => array_map(static function (array $row): array {
                $starts = (int) $row['starts'];
                $completions = (int) $row['completions'];
                return [
                    'view_key' => (string) $row['view_key'],
                    'starts' => $starts,
                    'manual_starts' => (int) $row['manual_starts'],
                    'autoplay_starts' => (int) $row['autoplay_starts'],
                    'completions' => $completions,
                    'stops' => (int) $row['stops'],
                    'completion_rate' => $starts > 0 ? ($completions / $starts) * 100 : 0,
                    'average_listened_seconds' => ((float) $row['average_listened_ms']) / 1000,
                ];
            }, $audioPages),
        ],
        'ip_addresses_included' => $includeIpAddresses,
        'recent_sessions' => array_map(static function (array $row) use ($includeIpAddresses): array {
            $session = [
                'session' => substr((string) $row['session_id'], 0, 8),
                'started_at' => (string) $row['started_at'],
                'last_seen_at' => (string) $row['last_seen_at'],
                'device' => (string) ($row['device_type'] ?: 'unknown'),
                'entry_page' => (string) ($row['entry_page_path'] ?: ''),
                'entry_view' => (string) ($row['entry_view_key'] ?: ''),
                'last_view' => (string) ($row['last_view_key'] ?: ''),
                'duration_seconds' => (int) $row['duration_seconds'],
                'clicks' => (int) $row['click_count'],
                'path_length' => (int) $row['path_length'],
            ];
            if ($includeIpAddresses) {
                $session['ip_address'] = (string) ($row['ip_address'] ?: 'unknown');
            }
            return $session;
        }, $recentSessions),
        'timeline' => $timeline,
    ];
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET' && ($_GET['dashboard'] ?? '') === '1') {
    $configuredToken = analyticsDashboardToken();
    $providedToken = trim((string) ($_SERVER['HTTP_X_ANALYTICS_DASHBOARD_TOKEN'] ?? ''));
    if ($configuredToken === '') {
        analyticsJsonResponse(503, ['ok' => false, 'error' => 'Dashboard access is not configured.']);
    }
    if ($providedToken === '' || !hash_equals($configuredToken, $providedToken)) {
        analyticsJsonResponse(401, ['ok' => false, 'error' => 'Invalid dashboard access code.']);
    }

    $period = (string) ($_GET['period'] ?? 'week');
    if (!in_array($period, ['day', 'week', 'month'], true)) {
        $period = 'week';
    }
    $includeIpAddresses = (string) ($_GET['include_ips'] ?? '') === '1';
    try {
        analyticsJsonResponse(200, [
            'ok' => true,
            'data' => analyticsDashboardData(analyticsDatabaseConnection(), $period, $includeIpAddresses),
        ]);
    } catch (Throwable $exception) {
        error_log('Test overview dashboard error: ' . $exception->getMessage());
        analyticsJsonResponse(503, ['ok' => false, 'error' => 'Dashboard data is temporarily unavailable.']);
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET' && ($_GET['health'] ?? '') === '1') {
    try {
        $pdo = analyticsDatabaseConnection();
        $tableStatement = $pdo->prepare(
            'SELECT COUNT(*) FROM information_schema.tables
             WHERE table_schema = DATABASE() AND table_name = :table_name'
        );
        $tableStatus = [];
        foreach (['test_overview_sessions', 'test_overview_events'] as $tableName) {
            $tableStatement->execute([':table_name' => $tableName]);
            $tableStatus[$tableName] = (int) $tableStatement->fetchColumn() === 1;
        }
        $columnStatement = $pdo->prepare(
            'SELECT COUNT(*) FROM information_schema.columns
             WHERE table_schema = DATABASE() AND table_name = :table_name AND column_name = :column_name'
        );
        $columnStatus = [];
        foreach ([
            'test_overview_sessions.application_key' => ['test_overview_sessions', 'application_key'],
            'test_overview_sessions.engaged_seconds' => ['test_overview_sessions', 'engaged_seconds'],
            'test_overview_sessions.ip_address' => ['test_overview_sessions', 'ip_address'],
            'test_overview_events.application_key' => ['test_overview_events', 'application_key'],
            'test_overview_events.referrer_path' => ['test_overview_events', 'referrer_path'],
            'test_overview_events.audio_source' => ['test_overview_events', 'audio_source'],
            'test_overview_events.audio_listened_ms' => ['test_overview_events', 'audio_listened_ms'],
            'test_overview_events.audio_autoplay_enabled' => ['test_overview_events', 'audio_autoplay_enabled'],
        ] as $statusKey => $columnParts) {
            $columnStatement->execute([
                ':table_name' => $columnParts[0],
                ':column_name' => $columnParts[1],
            ]);
            $columnStatus[$statusKey] = (int) $columnStatement->fetchColumn() === 1;
        }
        $allTablesReady = !in_array(false, $tableStatus, true)
            && !in_array(false, $columnStatus, true);
        analyticsJsonResponse($allTablesReady ? 200 : 503, [
            'ok' => $allTablesReady,
            'php_version' => PHP_VERSION,
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'database_connection' => true,
            'tables' => $tableStatus,
            'columns' => $columnStatus,
            'config_source' => is_file(__DIR__ . '/testoverview-analytics-config.php')
                ? 'testoverview-analytics-config.php'
                : 'fallback',
            'dashboard_configured' => analyticsDashboardToken() !== '',
        ]);
    } catch (Throwable $exception) {
        error_log('Test overview analytics health error: ' . $exception->getMessage());
        analyticsJsonResponse(503, [
            'ok' => false,
            'php_version' => PHP_VERSION,
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'database_connection' => false,
            'error' => 'Database connection failed. Check the analytics configuration.',
        ]);
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    analyticsJsonResponse(405, ['ok' => false, 'error' => 'Method not allowed.']);
}

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$requestHost = strtolower((string) preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? ''));
if ($origin !== '') {
    $originHost = strtolower((string) (parse_url($origin, PHP_URL_HOST) ?: ''));
    if ($originHost === '' || $requestHost === '' || $originHost !== $requestHost) {
        analyticsJsonResponse(403, ['ok' => false, 'error' => 'Cross-origin analytics requests are not allowed.']);
    }
}

$rawBody = file_get_contents('php://input');
if (!is_string($rawBody) || $rawBody === '' || strlen($rawBody) > 65536) {
    analyticsJsonResponse(400, ['ok' => false, 'error' => 'Invalid request body.']);
}

try {
    $payload = json_decode($rawBody, true, 32, JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    analyticsJsonResponse(400, ['ok' => false, 'error' => 'Invalid JSON.']);
}

$events = is_array($payload) ? ($payload['events'] ?? null) : null;
if (!is_array($events) || $events === [] || count($events) > 40) {
    analyticsJsonResponse(422, ['ok' => false, 'error' => 'Provide between 1 and 40 events.']);
}

$allowedEventTypes = [
    'page_view', 'view_change', 'click', 'control_change', 'heartbeat', 'session_end',
    'audio_autoplay_initial', 'audio_autoplay_toggle', 'audio_start', 'audio_pause',
    'audio_resume', 'audio_complete', 'audio_stop', 'audio_error'
];
$normalisedEvents = [];
foreach ($events as $event) {
    if (!is_array($event)) {
        continue;
    }

    $sessionId = analyticsIdentifier($event['session_id'] ?? null);
    $visitorId = analyticsIdentifier($event['visitor_id'] ?? null);
    $eventType = analyticsString($event['event_type'] ?? null, 32);
    $eventSequence = analyticsUnsignedInt($event['event_sequence'] ?? null, 2147483647);
    if ($sessionId === null || $visitorId === null || $eventSequence === null || !in_array($eventType, $allowedEventTypes, true)) {
        continue;
    }

    $metadataJson = null;
    if (isset($event['metadata']) && is_array($event['metadata'])) {
        $encodedMetadata = json_encode($event['metadata'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if (is_string($encodedMetadata) && strlen($encodedMetadata) <= 4096) {
            $metadataJson = $encodedMetadata;
        }
    }

    $pointerType = analyticsString($event['pointer_type'] ?? null, 20);
    if ($pointerType !== null && !in_array($pointerType, ['mouse', 'pen', 'touch', 'keyboard', 'unknown'], true)) {
        $pointerType = 'unknown';
    }

    $applicationKey = analyticsString($event['application_key'] ?? null, 64) ?: 'test_overview';
    if (!preg_match('/^[a-z0-9_-]+$/', $applicationKey)) {
        $applicationKey = 'test_overview';
    }

    $audioSource = analyticsString($event['audio_source'] ?? null, 24);
    if ($audioSource !== null && !in_array($audioSource, ['manual', 'autoplay'], true)) {
        $audioSource = null;
    }
    $audioAutoplayEnabled = null;
    if (array_key_exists('audio_autoplay_enabled', $event)) {
        $audioAutoplayEnabled = filter_var(
            $event['audio_autoplay_enabled'],
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );
        $audioAutoplayEnabled = $audioAutoplayEnabled === null ? null : ($audioAutoplayEnabled ? 1 : 0);
    }

    $normalisedEvents[] = [
        'session_id' => $sessionId,
        'visitor_id' => $visitorId,
        'application_key' => $applicationKey,
        'session_started_at' => analyticsDateTime($event['session_started_at'] ?? null),
        'event_sequence' => $eventSequence,
        'event_type' => $eventType,
        'client_occurred_at' => analyticsDateTime($event['client_occurred_at'] ?? null),
        'view_key' => analyticsString($event['view_key'] ?? null, 120),
        'previous_view_key' => analyticsString($event['previous_view_key'] ?? null, 120),
        'target_view_key' => analyticsString($event['target_view_key'] ?? null, 120),
        'page_path' => analyticsString($event['page_path'] ?? null, 255),
        'page_value' => analyticsString($event['page_value'] ?? null, 120),
        'referrer_path' => analyticsString($event['referrer_path'] ?? null, 512),
        'element_kind' => analyticsString($event['element_kind'] ?? null, 40),
        'element_key' => analyticsString($event['element_key'] ?? null, 160),
        'element_id' => analyticsString($event['element_id'] ?? null, 120),
        'element_label' => analyticsString($event['element_label'] ?? null, 255),
        'element_classes' => analyticsString($event['element_classes'] ?? null, 255),
        'element_href' => analyticsString($event['element_href'] ?? null, 512),
        'pointer_type' => $pointerType,
        'click_x' => analyticsUnsignedInt($event['click_x'] ?? null, 65535),
        'click_y' => analyticsUnsignedInt($event['click_y'] ?? null, 65535),
        'viewport_width' => analyticsUnsignedInt($event['viewport_width'] ?? null, 65535),
        'viewport_height' => analyticsUnsignedInt($event['viewport_height'] ?? null, 65535),
        'device_type' => analyticsString($event['device_type'] ?? null, 20),
        'active_seconds_delta' => analyticsUnsignedInt($event['active_seconds_delta'] ?? null, 60) ?? 0,
        'audio_source' => $audioSource,
        'audio_listened_ms' => analyticsUnsignedInt($event['audio_listened_ms'] ?? null, 86400000),
        'audio_autoplay_enabled' => $audioAutoplayEnabled,
        'metadata' => $metadataJson,
    ];
}

if ($normalisedEvents === []) {
    analyticsJsonResponse(422, ['ok' => false, 'error' => 'No valid events were provided.']);
}

try {
    $pdo = analyticsDatabaseConnection();
    $pdo->beginTransaction();
    $clientIpAddress = analyticsClientIpAddress();

    $sessionStatement = $pdo->prepare(
        'INSERT INTO test_overview_sessions (
            session_id, visitor_id, application_key, started_at, last_seen_at, engaged_seconds, entry_view_key, last_view_key,
            entry_page_path, entry_referrer_path, device_type, viewport_width, viewport_height, ip_address
        ) VALUES (
            :session_id, :visitor_id, :application_key, COALESCE(:started_at, CURRENT_TIMESTAMP(3)), CURRENT_TIMESTAMP(3),
            :engaged_seconds_delta, :entry_view_key, :last_view_key, :entry_page_path, :entry_referrer_path,
            :device_type, :viewport_width, :viewport_height, :ip_address
        ) ON DUPLICATE KEY UPDATE
            last_seen_at = CURRENT_TIMESTAMP(3),
            engaged_seconds = engaged_seconds + VALUES(engaged_seconds),
            application_key = VALUES(application_key),
            last_view_key = VALUES(last_view_key),
            device_type = VALUES(device_type),
            viewport_width = VALUES(viewport_width),
            viewport_height = VALUES(viewport_height),
            ip_address = COALESCE(VALUES(ip_address), ip_address)'
    );

    $eventStatement = $pdo->prepare(
        'INSERT INTO test_overview_events (
            session_id, event_sequence, application_key, event_type, client_occurred_at, view_key,
            previous_view_key, target_view_key, page_path, page_value, referrer_path, element_kind,
            element_key, element_id, element_label, element_classes, element_href,
            pointer_type, click_x, click_y, viewport_width, viewport_height,
            audio_source, audio_listened_ms, audio_autoplay_enabled, metadata
        ) VALUES (
            :session_id, :event_sequence, :application_key, :event_type, :client_occurred_at, :view_key,
            :previous_view_key, :target_view_key, :page_path, :page_value, :referrer_path, :element_kind,
            :element_key, :element_id, :element_label, :element_classes, :element_href,
            :pointer_type, :click_x, :click_y, :viewport_width, :viewport_height,
            :audio_source, :audio_listened_ms, :audio_autoplay_enabled, :metadata
        ) ON DUPLICATE KEY UPDATE id = id'
    );

    $acceptedCount = 0;
    foreach ($normalisedEvents as $event) {
        $sessionStatement->execute([
            ':session_id' => $event['session_id'],
            ':visitor_id' => $event['visitor_id'],
            ':application_key' => $event['application_key'],
            ':started_at' => $event['session_started_at'],
            ':engaged_seconds_delta' => $event['active_seconds_delta'],
            ':entry_view_key' => $event['view_key'],
            ':last_view_key' => $event['target_view_key'] ?? $event['view_key'],
            ':entry_page_path' => $event['page_path'],
            ':entry_referrer_path' => $event['referrer_path'],
            ':device_type' => $event['device_type'],
            ':viewport_width' => $event['viewport_width'],
            ':viewport_height' => $event['viewport_height'],
            ':ip_address' => $clientIpAddress,
        ]);

        if ($event['event_type'] === 'heartbeat') {
            continue;
        }

        $eventStatement->execute([
            ':session_id' => $event['session_id'],
            ':event_sequence' => $event['event_sequence'],
            ':application_key' => $event['application_key'],
            ':event_type' => $event['event_type'],
            ':client_occurred_at' => $event['client_occurred_at'],
            ':view_key' => $event['view_key'],
            ':previous_view_key' => $event['previous_view_key'],
            ':target_view_key' => $event['target_view_key'],
            ':page_path' => $event['page_path'],
            ':page_value' => $event['page_value'],
            ':referrer_path' => $event['referrer_path'],
            ':element_kind' => $event['element_kind'],
            ':element_key' => $event['element_key'],
            ':element_id' => $event['element_id'],
            ':element_label' => $event['element_label'],
            ':element_classes' => $event['element_classes'],
            ':element_href' => $event['element_href'],
            ':pointer_type' => $event['pointer_type'],
            ':click_x' => $event['click_x'],
            ':click_y' => $event['click_y'],
            ':viewport_width' => $event['viewport_width'],
            ':viewport_height' => $event['viewport_height'],
            ':audio_source' => $event['audio_source'],
            ':audio_listened_ms' => $event['audio_listened_ms'],
            ':audio_autoplay_enabled' => $event['audio_autoplay_enabled'],
            ':metadata' => $event['metadata'],
        ]);
        $acceptedCount += $eventStatement->rowCount();
    }

    $pdo->commit();
    analyticsJsonResponse(202, ['ok' => true, 'accepted' => $acceptedCount]);
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Test overview analytics error: ' . $exception->getMessage());
    analyticsJsonResponse(503, ['ok' => false, 'error' => 'Analytics storage is temporarily unavailable.']);
}
