ALTER TABLE test_overview_sessions
    ADD COLUMN engaged_seconds INT UNSIGNED NOT NULL DEFAULT 0 AFTER last_seen_at;
