ALTER TABLE test_overview_events
    ADD COLUMN audio_source VARCHAR(24) DEFAULT NULL AFTER viewport_height,
    ADD COLUMN audio_listened_ms INT UNSIGNED DEFAULT NULL AFTER audio_source,
    ADD COLUMN audio_autoplay_enabled TINYINT(1) DEFAULT NULL AFTER audio_listened_ms,
    ADD KEY idx_test_overview_events_audio (event_type, audio_source);
