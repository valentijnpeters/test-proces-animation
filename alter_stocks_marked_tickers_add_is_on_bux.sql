ALTER TABLE stocks_marked_tickers
    ADD COLUMN is_on_bux TINYINT(1) NOT NULL DEFAULT 0
    AFTER feeling_slider_position;
