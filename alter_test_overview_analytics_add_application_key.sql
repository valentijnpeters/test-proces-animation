ALTER TABLE test_overview_sessions
    ADD COLUMN application_key VARCHAR(64) NOT NULL DEFAULT 'test_overview' AFTER visitor_id,
    ADD COLUMN entry_referrer_path VARCHAR(512) DEFAULT NULL AFTER entry_page_path,
    ADD KEY idx_test_overview_sessions_application (application_key);

ALTER TABLE test_overview_events
    ADD COLUMN application_key VARCHAR(64) NOT NULL DEFAULT 'test_overview' AFTER event_sequence,
    ADD COLUMN referrer_path VARCHAR(512) DEFAULT NULL AFTER page_value,
    ADD KEY idx_test_overview_events_application (application_key);

