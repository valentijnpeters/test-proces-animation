ALTER TABLE test_overview_sessions
    ADD COLUMN ip_address VARCHAR(45) CHARACTER SET ascii DEFAULT NULL AFTER viewport_height;
