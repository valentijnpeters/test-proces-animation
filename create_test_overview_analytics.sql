CREATE TABLE IF NOT EXISTS test_overview_sessions (
    session_id CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    visitor_id CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    application_key VARCHAR(64) NOT NULL DEFAULT 'test_overview',
    started_at DATETIME(3) NOT NULL,
    last_seen_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    engaged_seconds INT UNSIGNED NOT NULL DEFAULT 0,
    entry_view_key VARCHAR(120) DEFAULT NULL,
    last_view_key VARCHAR(120) DEFAULT NULL,
    entry_page_path VARCHAR(255) DEFAULT NULL,
    entry_referrer_path VARCHAR(512) DEFAULT NULL,
    device_type VARCHAR(20) DEFAULT NULL,
    viewport_width SMALLINT UNSIGNED DEFAULT NULL,
    viewport_height SMALLINT UNSIGNED DEFAULT NULL,
    ip_address VARCHAR(45) CHARACTER SET ascii DEFAULT NULL,
    PRIMARY KEY (session_id),
    KEY idx_test_overview_sessions_visitor (visitor_id),
    KEY idx_test_overview_sessions_application (application_key),
    KEY idx_test_overview_sessions_started (started_at),
    KEY idx_test_overview_sessions_last_seen (last_seen_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS test_overview_events (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    session_id CHAR(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    event_sequence INT UNSIGNED NOT NULL,
    application_key VARCHAR(64) NOT NULL DEFAULT 'test_overview',
    event_type VARCHAR(32) NOT NULL,
    client_occurred_at DATETIME(3) DEFAULT NULL,
    server_received_at DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    view_key VARCHAR(120) DEFAULT NULL,
    previous_view_key VARCHAR(120) DEFAULT NULL,
    target_view_key VARCHAR(120) DEFAULT NULL,
    page_path VARCHAR(255) DEFAULT NULL,
    page_value VARCHAR(120) DEFAULT NULL,
    referrer_path VARCHAR(512) DEFAULT NULL,
    element_kind VARCHAR(40) DEFAULT NULL,
    element_key VARCHAR(160) DEFAULT NULL,
    element_id VARCHAR(120) DEFAULT NULL,
    element_label VARCHAR(255) DEFAULT NULL,
    element_classes VARCHAR(255) DEFAULT NULL,
    element_href VARCHAR(512) DEFAULT NULL,
    pointer_type VARCHAR(20) DEFAULT NULL,
    click_x SMALLINT UNSIGNED DEFAULT NULL,
    click_y SMALLINT UNSIGNED DEFAULT NULL,
    viewport_width SMALLINT UNSIGNED DEFAULT NULL,
    viewport_height SMALLINT UNSIGNED DEFAULT NULL,
    audio_source VARCHAR(24) DEFAULT NULL,
    audio_listened_ms INT UNSIGNED DEFAULT NULL,
    audio_autoplay_enabled TINYINT(1) DEFAULT NULL,
    metadata JSON NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_test_overview_event_sequence (session_id, event_sequence),
    KEY idx_test_overview_events_received (server_received_at),
    KEY idx_test_overview_events_type (event_type),
    KEY idx_test_overview_events_application (application_key),
    KEY idx_test_overview_events_view (view_key),
    KEY idx_test_overview_events_element (element_key),
    KEY idx_test_overview_events_audio (event_type, audio_source),
    KEY idx_test_overview_events_path (previous_view_key, target_view_key),
    CONSTRAINT fk_test_overview_events_session
        FOREIGN KEY (session_id) REFERENCES test_overview_sessions (session_id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Reconstruct one user's chronological path:
-- SELECT event_sequence, event_type, view_key, target_view_key, element_label, client_occurred_at
-- FROM test_overview_events
-- WHERE session_id = 'replace-with-session-id'
-- ORDER BY event_sequence;

-- Find the most-used controls:
-- SELECT element_key, element_label, COUNT(*) AS click_count
-- FROM test_overview_events
-- WHERE event_type = 'click'
-- GROUP BY element_key, element_label
-- ORDER BY click_count DESC;

-- Compare the filenames through which Test Overview was opened:
-- SELECT application_key, page_path, COUNT(*) AS event_count
-- FROM test_overview_events
-- GROUP BY application_key, page_path
-- ORDER BY event_count DESC;
