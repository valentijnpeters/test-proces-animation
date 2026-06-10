<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Overview</title>
    <style>
        :root {
            --bg-top: #081d3d;
            --bg-mid: #0d3b77;
            --bg-bottom: #5ea6d3;
            --panel-border: rgba(255, 255, 255, 0.16);
            --panel-glow: rgba(140, 232, 255, 0.22);
            --gold-soft: #ffe5a2;
            --cyan-soft: #a7efff;
            --text-soft: rgba(239, 248, 255, 0.92);
            --shadow-3d:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 18px 34px rgba(0,0,0,0.26);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            min-height: 100%;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #ffffff;
            background:
                radial-gradient(circle at top, rgba(255,255,255,0.12), transparent 30%),
                linear-gradient(180deg, var(--bg-top), var(--bg-mid) 42%, var(--bg-bottom));
        }

        .page-shell {
            width: min(1120px, calc(100% - 28px));
            margin: 0 auto;
            padding: 28px 0 40px;
        }

        .overview-card {
            position: relative;
            overflow: hidden;
            border-radius: 36px;
            border: 1px solid var(--panel-border);
            background:
                linear-gradient(160deg, rgba(8, 37, 82, 0.96), rgba(3, 22, 52, 0.98)),
                linear-gradient(120deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow: var(--shadow-3d);
            padding: 28px 28px 30px;
        }

        .overview-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 14% 12%, rgba(255,255,255,0.12), transparent 26%),
                radial-gradient(circle at 88% 8%, rgba(167, 239, 255, 0.12), transparent 20%);
            pointer-events: none;
        }

        .overview-header {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .overview-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 0 0 auto;
        }

        .narration-control {
            display: inline-flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
            padding: 8px;
            border: 1px solid rgba(197, 206, 218, 0.2);
            border-radius: 22px;
            background:
                linear-gradient(180deg, rgba(64, 74, 88, 0.36), rgba(17, 22, 30, 0.74)),
                linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 12px 22px rgba(0,0,0,0.2);
        }

        .autoplay-toggle {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 78px;
            min-height: 54px;
            padding: 6px 9px 7px;
            border: 1px solid rgba(197, 206, 218, 0.24);
            border-radius: 18px;
            cursor: pointer;
            color: rgba(241, 244, 248, 0.94);
            background:
                linear-gradient(180deg, rgba(67, 76, 91, 0.92), rgba(21, 26, 35, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 7px 14px rgba(0,0,0,0.18);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .autoplay-toggle:hover,
        .autoplay-toggle:focus-visible {
            transform: translateY(-1px);
            border-color: rgba(222, 228, 236, 0.42);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 10px 18px rgba(0,0,0,0.22),
                0 0 14px rgba(199, 208, 220, 0.14);
            outline: none;
        }

        .autoplay-toggle:active {
            transform: translateY(2px);
        }

        .autoplay-toggle.is-active {
            border-color: rgba(223, 229, 236, 0.46);
            color: #ffffff;
            background:
                linear-gradient(180deg, rgba(101, 112, 127, 0.96), rgba(34, 41, 52, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
        }

        .autoplay-toggle-label {
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.52rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            line-height: 1;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .autoplay-toggle-track {
            position: relative;
            width: 30px;
            height: 16px;
            border-radius: 999px;
            background: linear-gradient(180deg, rgba(18, 23, 31, 0.98), rgba(57, 67, 82, 0.96));
            box-shadow:
                inset 0 1px 2px rgba(0,0,0,0.34),
                inset 0 1px 0 rgba(255,255,255,0.06);
        }

        .autoplay-toggle-knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: linear-gradient(180deg, #f4f7fb, #aeb8c7);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.36),
                0 2px 5px rgba(0,0,0,0.22);
            transition: transform 0.18s ease, background 0.18s ease;
        }

        .autoplay-toggle.is-active .autoplay-toggle-knob {
            transform: translateX(14px);
            background: linear-gradient(180deg, #ffffff, #cfd7e3);
        }

        .overview-title-wrap {
            min-width: 0;
        }

        .overview-kicker {
            margin: 0 0 6px;
            color: rgba(188, 232, 255, 0.7);
            font-size: 0.76rem;
            font-weight: 900;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        .overview-title {
            margin: 0;
            color: var(--gold-soft);
            font-size: clamp(1.8rem, 3vw, 2.55rem);
            line-height: 1.02;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .title-inline-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px 0 0;
            padding: 0.2em 0.58em 0.24em;
            border: 1px solid rgba(255, 231, 161, 0.42);
            border-radius: 16px;
            cursor: pointer;
            color: #fff5cc;
            font: inherit;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background:
                radial-gradient(circle at 22% 18%, rgba(255,255,255,0.3), transparent 30%),
                linear-gradient(180deg, rgba(255, 220, 123, 0.34), rgba(154, 104, 18, 0.5)),
                linear-gradient(180deg, rgba(12, 56, 110, 0.98), rgba(4, 24, 53, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.24),
                inset 0 -8px 14px rgba(0,0,0,0.16),
                0 7px 0 rgba(47, 28, 4, 0.7),
                0 14px 24px rgba(0,0,0,0.22);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
            vertical-align: middle;
        }

        .title-inline-button:hover,
        .title-inline-button:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(255, 239, 194, 0.62);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.28),
                inset 0 -8px 14px rgba(0,0,0,0.16),
                0 7px 0 rgba(47, 28, 4, 0.7),
                0 18px 28px rgba(0,0,0,0.26),
                0 0 16px rgba(255, 226, 145, 0.16);
            outline: none;
        }

        .title-inline-button:active {
            transform: translateY(3px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.22),
                inset 0 -6px 12px rgba(0,0,0,0.14),
                0 3px 0 rgba(47, 28, 4, 0.78),
                0 9px 16px rgba(0,0,0,0.2);
        }

        .overview-breadcrumb {
            margin: 10px 0 0;
            color: rgba(233, 246, 255, 0.78);
            font-size: 0.86rem;
            line-height: 1.45;
            letter-spacing: 0.04em;
        }

        .back-button {
            width: 54px;
            height: 54px;
            flex: 0 0 auto;
            border: 0;
            border-radius: 18px;
            cursor: pointer;
            color: #f7fbff;
            background:
                radial-gradient(circle at 26% 20%, rgba(255,255,255,0.38), transparent 24%),
                linear-gradient(145deg, rgba(255, 210, 106, 0.28), rgba(13, 50, 95, 0.98) 48%, rgba(3, 19, 43, 0.98)),
                rgba(3, 25, 54, 0.92);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.3),
                inset 0 -8px 14px rgba(0,0,0,0.24),
                0 8px 0 rgba(2, 15, 36, 0.86),
                0 18px 30px rgba(0,0,0,0.28);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .back-button:hover,
        .back-button:focus-visible {
            transform: translateY(-2px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.34),
                inset 0 -8px 14px rgba(0,0,0,0.24),
                0 8px 0 rgba(2, 15, 36, 0.86),
                0 22px 34px rgba(0,0,0,0.32),
                0 0 20px rgba(167, 239, 255, 0.14);
            outline: none;
        }

        .back-button:active {
            transform: translateY(4px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.26),
                inset 0 -6px 12px rgba(0,0,0,0.22),
                0 3px 0 rgba(2, 15, 36, 0.9),
                0 10px 18px rgba(0,0,0,0.24);
        }

        .back-button[hidden] {
            display: none;
        }

        .back-button svg {
            width: 22px;
            height: 22px;
        }

        .narration-button {
            width: 54px;
            height: 54px;
            flex: 0 0 auto;
            border: 1px solid rgba(197, 206, 218, 0.28);
            border-radius: 18px;
            cursor: pointer;
            color: #f1f4f8;
            background:
                radial-gradient(circle at 26% 20%, rgba(255,255,255,0.28), transparent 24%),
                linear-gradient(145deg, rgba(223, 228, 235, 0.26), rgba(86, 96, 112, 0.98) 48%, rgba(29, 34, 43, 0.99)),
                rgba(23, 28, 36, 0.96);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.24),
                inset 0 -8px 14px rgba(0,0,0,0.22),
                0 8px 0 rgba(2, 15, 36, 0.86),
                0 18px 30px rgba(0,0,0,0.24);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, opacity 0.18s ease;
        }

        .narration-button:hover,
        .narration-button:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(221, 227, 236, 0.46);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.28),
                inset 0 -8px 14px rgba(0,0,0,0.22),
                0 8px 0 rgba(2, 15, 36, 0.86),
                0 22px 34px rgba(0,0,0,0.28),
                0 0 18px rgba(200, 208, 220, 0.16);
            outline: none;
        }

        .narration-button:active,
        .narration-button.is-playing {
            transform: translateY(3px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.22),
                inset 0 -6px 12px rgba(0,0,0,0.18),
                0 3px 0 rgba(2, 15, 36, 0.9),
                0 10px 18px rgba(0,0,0,0.22);
        }

        .narration-button.is-playing {
            border-color: rgba(232, 237, 244, 0.58);
            color: #ffffff;
        }

        .narration-button.is-paused {
            border-color: rgba(255, 228, 176, 0.52);
            color: #fff6df;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.22),
                inset 0 -6px 12px rgba(0,0,0,0.18),
                0 5px 0 rgba(2, 15, 36, 0.88),
                0 14px 22px rgba(0,0,0,0.24);
        }

        .narration-button[disabled] {
            cursor: not-allowed;
            opacity: 0.42;
            filter: saturate(0.4);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 5px 0 rgba(2, 15, 36, 0.76),
                0 10px 16px rgba(0,0,0,0.16);
        }

        .narration-button svg {
            width: 22px;
            height: 22px;
        }

        .overview-content {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
        }

        .edit-mode-toggle {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 54px;
            padding: 10px 14px 10px 16px;
            border: 1px solid rgba(182, 229, 255, 0.18);
            border-radius: 20px;
            cursor: pointer;
            color: #eef8ff;
            background:
                linear-gradient(180deg, rgba(16, 68, 128, 0.94), rgba(6, 30, 64, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 10px 20px rgba(0,0,0,0.2),
                0 4px 0 rgba(2, 16, 38, 0.76);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .edit-mode-toggle:hover,
        .edit-mode-toggle:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(255, 225, 146, 0.38);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.2),
                0 16px 26px rgba(0,0,0,0.24),
                0 4px 0 rgba(2, 16, 38, 0.76),
                0 0 18px rgba(140, 232, 255, 0.14);
            outline: none;
        }

        .edit-mode-toggle:active {
            transform: translateY(3px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 5px 12px rgba(0,0,0,0.2),
                0 2px 0 rgba(2, 16, 38, 0.82);
        }

        .edit-mode-toggle.is-active {
            border-color: rgba(255, 225, 146, 0.42);
            background:
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.24), transparent 24%),
                linear-gradient(180deg, rgba(255, 214, 112, 0.34), rgba(148, 103, 18, 0.5)),
                linear-gradient(180deg, rgba(12, 56, 110, 0.98), rgba(5, 26, 56, 0.98));
        }

        .edit-mode-toggle-label {
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .edit-mode-toggle-track {
            position: relative;
            width: 56px;
            height: 26px;
            flex: 0 0 auto;
            border-radius: 999px;
            background:
                linear-gradient(180deg, rgba(3, 19, 40, 0.94), rgba(16, 62, 116, 0.88));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                inset 0 -6px 12px rgba(0,0,0,0.18);
        }

        .edit-mode-toggle-knob {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background:
                radial-gradient(circle at 30% 28%, rgba(255,255,255,0.7), rgba(255,255,255,0.06) 54%),
                linear-gradient(180deg, rgba(191, 237, 255, 0.96), rgba(84, 180, 255, 0.92));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.34),
                0 4px 8px rgba(0,0,0,0.22);
            transition: transform 0.22s ease, background 0.22s ease, box-shadow 0.22s ease;
        }

        .edit-mode-toggle.is-active .edit-mode-toggle-knob {
            transform: translateX(30px);
            background:
                radial-gradient(circle at 30% 28%, rgba(255,255,255,0.74), rgba(255,255,255,0.08) 54%),
                linear-gradient(180deg, rgba(255, 232, 155, 0.98), rgba(255, 178, 64, 0.94));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.36),
                0 4px 10px rgba(62, 40, 8, 0.3);
        }

        .edit-panel {
            border-radius: 28px;
            border: 1px solid rgba(255, 225, 146, 0.22);
            background:
                linear-gradient(180deg, rgba(10, 43, 88, 0.94), rgba(5, 25, 54, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 18px 30px rgba(0,0,0,0.22);
            padding: 22px 22px 20px;
        }

        .edit-panel-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
        }

        .edit-panel-title {
            margin: 0;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .edit-panel-copy {
            margin: 7px 0 0;
            color: rgba(230, 244, 255, 0.8);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 58ch;
        }

        .edit-panel-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 12px;
            border-radius: 999px;
            color: #fff7d6;
            font-size: 0.76rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            background:
                linear-gradient(180deg, rgba(255, 222, 134, 0.3), rgba(90, 56, 8, 0.34)),
                rgba(8, 28, 58, 0.88);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 8px 14px rgba(0,0,0,0.18);
        }

        .edit-builder {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            margin-top: 14px;
            align-items: center;
        }

        .edit-builder-input {
            width: 100%;
            min-height: 56px;
            border: 1px solid rgba(180, 228, 255, 0.2);
            border-radius: 18px;
            padding: 0 18px;
            color: #f3faff;
            font-size: 0.98rem;
            background:
                linear-gradient(180deg, rgba(6, 28, 58, 0.98), rgba(10, 44, 86, 0.92));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 10px 18px rgba(0,0,0,0.16);
        }

        .edit-builder-input::placeholder {
            color: rgba(208, 232, 248, 0.5);
        }

        .edit-builder-input:focus {
            outline: none;
            border-color: rgba(255, 225, 146, 0.42);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 12px 20px rgba(0,0,0,0.18),
                0 0 0 2px rgba(255, 225, 146, 0.14);
        }

        .edit-builder-submit {
            min-height: 56px;
            padding: 0 20px;
            border: 1px solid rgba(255, 225, 146, 0.26);
            border-radius: 18px;
            cursor: pointer;
            color: #fff7dc;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.88rem;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            background:
                linear-gradient(180deg, rgba(255, 214, 111, 0.3), rgba(139, 91, 14, 0.46)),
                linear-gradient(180deg, rgba(9, 40, 84, 0.98), rgba(4, 22, 48, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 10px 18px rgba(0,0,0,0.2),
                0 4px 0 rgba(52, 34, 7, 0.74);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .edit-builder-submit:hover,
        .edit-builder-submit:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(255, 233, 170, 0.42);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 16px 24px rgba(0,0,0,0.22),
                0 4px 0 rgba(52, 34, 7, 0.74),
                0 0 16px rgba(255, 226, 145, 0.14);
            outline: none;
        }

        .edit-builder-submit:active {
            transform: translateY(3px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.14),
                0 6px 12px rgba(0,0,0,0.18),
                0 2px 0 rgba(52, 34, 7, 0.8);
        }

        .edit-custom-list {
            margin-top: 16px;
        }

        .edit-custom-heading {
            margin: 0 0 12px;
            color: rgba(224, 241, 255, 0.72);
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .edit-empty {
            margin: 0;
            color: rgba(213, 234, 248, 0.66);
            font-size: 0.9rem;
            line-height: 1.55;
        }

        .choice-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .choice-grid.is-single {
            grid-template-columns: 1fr;
        }

        .choice-grid.is-decision {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .choice-grid.is-triple {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .choice-button {
            min-height: 210px;
            width: 100%;
            border: 1px solid rgba(182, 229, 255, 0.18);
            border-radius: 28px;
            cursor: pointer;
            padding: 28px 24px;
            text-align: left;
            color: #f5fbff;
            background:
                linear-gradient(180deg, rgba(17, 74, 138, 0.95), rgba(7, 34, 72, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 16px 28px rgba(0,0,0,0.22),
                0 4px 0 rgba(2, 16, 38, 0.78);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .choice-button:hover,
        .choice-button:focus-visible {
            transform: translateY(-3px);
            border-color: rgba(255, 225, 146, 0.36);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.22),
                0 22px 34px rgba(0,0,0,0.26),
                0 4px 0 rgba(2, 16, 38, 0.78),
                0 0 20px rgba(140, 232, 255, 0.14);
            outline: none;
        }

        .choice-button:active {
            transform: translateY(4px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 8px 16px rgba(0,0,0,0.22),
                0 2px 0 rgba(2, 16, 38, 0.82);
        }
        .choice-button.is-tone-blue {
            background:
                linear-gradient(180deg, rgba(17, 74, 138, 0.95), rgba(7, 34, 72, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
        }
        .choice-button.is-tone-green {
            background:
                linear-gradient(180deg, rgba(33, 135, 84, 0.96), rgba(13, 74, 48, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.16), rgba(255,255,255,0));
            border-color: rgba(171, 255, 211, 0.22);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.2),
                0 16px 28px rgba(0,0,0,0.22),
                0 4px 0 rgba(5, 40, 24, 0.72);
        }
        .choice-button.is-tone-green .choice-label {
            color: #ecfff3;
        }
        .choice-button.is-tone-red {
            background:
                linear-gradient(180deg, rgba(164, 36, 56, 0.96), rgba(88, 14, 30, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            border-color: rgba(255, 186, 196, 0.26);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 16px 28px rgba(0,0,0,0.22),
                0 4px 0 rgba(64, 8, 18, 0.78);
        }
        .choice-button.is-tone-red .choice-label {
            color: #fff0f3;
        }
        .choice-button.is-tone-slate {
            background:
                linear-gradient(180deg, rgba(96, 107, 122, 0.92), rgba(29, 35, 45, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            border-color: rgba(212, 220, 230, 0.22);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 14px 24px rgba(0,0,0,0.2),
                0 4px 0 rgba(16, 21, 28, 0.82);
        }
        .choice-button.is-tone-slate .choice-label {
            color: #f6f9fd;
        }
        .choice-button.is-compact {
            min-height: 118px;
            padding: 20px 18px;
        }
        .choice-button.is-small {
            min-height: 76px;
            padding: 14px 16px;
            border-radius: 20px;
        }
        .choice-button.is-span-full {
            grid-column: 1 / -1;
        }
        .choice-button.is-compact .choice-label {
            font-size: clamp(1rem, 1.45vw, 1.2rem);
            line-height: 1.12;
        }
        .choice-button.is-small .choice-label {
            font-size: clamp(0.88rem, 1.15vw, 1rem);
            line-height: 1.2;
            letter-spacing: 0.05em;
        }

        .choice-label {
            display: block;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(1.32rem, 2vw, 1.9rem);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1.08;
        }

        .choice-copy {
            display: block;
            margin-top: 14px;
            color: rgba(226, 243, 255, 0.78);
            font-size: 0.92rem;
            line-height: 1.55;
            max-width: 30ch;
        }

        .decision-layout {
            display: grid;
            gap: 18px;
        }

        .decision-status-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .decision-intent-shell {
            border-radius: 28px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                linear-gradient(180deg, rgba(8, 38, 79, 0.94), rgba(4, 22, 48, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.1),
                0 18px 30px rgba(0,0,0,0.22);
            padding: 18px;
        }

        .decision-intent-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .decision-intent-column {
            display: grid;
            gap: 14px;
        }

        .knowledge-overlap-shell {
            margin-top: clamp(52px, 9vw, 96px);
        }

        .knowledge-overlap-panel {
            border-radius: 28px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                linear-gradient(180deg, rgba(8, 38, 79, 0.94), rgba(4, 22, 48, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.1),
                0 18px 30px rgba(0,0,0,0.22);
            overflow: hidden;
        }

        .knowledge-overlap-summary {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 68px;
            padding: 18px 22px;
            cursor: pointer;
            user-select: none;
        }

        .knowledge-overlap-summary::-webkit-details-marker {
            display: none;
        }

        .knowledge-overlap-summary-text {
            display: grid;
            gap: 5px;
        }

        .knowledge-overlap-summary-kicker {
            color: rgba(167, 239, 255, 0.72);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.16em;
            line-height: 1;
            text-transform: uppercase;
        }

        .knowledge-overlap-summary-title {
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1.02rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            line-height: 1.08;
            text-transform: uppercase;
        }

        .knowledge-overlap-summary-icon {
            width: 32px;
            height: 32px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #f5fbff;
            font-size: 1.18rem;
            font-weight: 900;
            background:
                radial-gradient(circle at 30% 24%, rgba(255,255,255,0.2), transparent 28%),
                linear-gradient(180deg, rgba(255, 221, 138, 0.9), rgba(137, 95, 24, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 8px 14px rgba(0,0,0,0.2);
            transition: transform 0.18s ease;
        }

        .knowledge-overlap-panel[open] .knowledge-overlap-summary-icon {
            transform: rotate(45deg);
        }

        .knowledge-overlap-body {
            display: grid;
            grid-template-columns: minmax(0, 0.95fr) minmax(0, 1.05fr);
            gap: 24px;
            padding: 6px 22px 22px;
            border-top: 1px solid rgba(184, 230, 255, 0.12);
            background:
                linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0));
        }

        .knowledge-overlap-visual {
            display: grid;
            align-content: start;
        }

        .knowledge-overlap-diagram {
            position: relative;
            width: min(100%, 520px);
            min-height: 320px;
            aspect-ratio: 1.38 / 1;
            margin: 0 auto;
        }

        .knowledge-overlap-ring {
            position: absolute;
            width: 44%;
            aspect-ratio: 1 / 1;
            border-radius: 999px;
            border: 4px solid rgba(184, 230, 255, 0.48);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
            background:
                radial-gradient(circle at 28% 24%, rgba(255,255,255,0.12), rgba(255,255,255,0.02) 42%, rgba(255,255,255,0) 70%);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 16px 24px rgba(0,0,0,0.16);
        }

        .knowledge-overlap-ring.is-domain {
            top: 2%;
            left: 7%;
            border-color: rgba(255, 222, 145, 0.9);
            color: #fff3cb;
        }

        .knowledge-overlap-ring.is-professional {
            top: 2%;
            right: 7%;
            border-color: rgba(167, 239, 255, 0.9);
            color: #e5faff;
        }

        .knowledge-overlap-ring.is-organizational {
            left: 50%;
            top: 37%;
            transform: translateX(-50%);
            border-color: rgba(162, 247, 222, 0.9);
            color: #ebfff7;
        }

        .knowledge-overlap-ring-label {
            display: grid;
            gap: 3px;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(0.78rem, 1.35vw, 0.98rem);
            font-weight: 900;
            letter-spacing: 0.04em;
            line-height: 1.08;
            text-transform: none;
            text-shadow: 0 1px 0 rgba(0,0,0,0.22);
        }

        .knowledge-overlap-copy {
            display: grid;
            gap: 12px;
            align-content: start;
        }

        .knowledge-overlap-note {
            margin: 0;
            padding: 16px 18px;
            border-radius: 20px;
            border: 1px solid rgba(184, 230, 255, 0.14);
            color: rgba(236, 246, 255, 0.9);
            font-size: 0.94rem;
            line-height: 1.62;
            background:
                linear-gradient(180deg, rgba(11, 50, 101, 0.72), rgba(5, 25, 50, 0.9)),
                linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 12px 20px rgba(0,0,0,0.16);
        }

        .knowledge-overlap-note-term {
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1rem;
            letter-spacing: 0.04em;
        }

        .expandable-action-shell {
            margin-top: clamp(52px, 9vw, 96px);
        }

        .expandable-action-panel {
            border-radius: 28px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                linear-gradient(180deg, rgba(8, 38, 79, 0.94), rgba(4, 22, 48, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.1),
                0 18px 30px rgba(0,0,0,0.22);
            overflow: hidden;
        }

        .expandable-action-summary {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 68px;
            padding: 18px 22px;
            cursor: pointer;
            user-select: none;
        }

        .expandable-action-summary::-webkit-details-marker {
            display: none;
        }

        .expandable-action-summary-text {
            display: grid;
            gap: 5px;
        }

        .expandable-action-summary-kicker {
            color: rgba(167, 239, 255, 0.72);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.16em;
            line-height: 1;
            text-transform: uppercase;
        }

        .expandable-action-summary-title {
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1.02rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            line-height: 1.08;
            text-transform: uppercase;
        }

        .expandable-action-summary-icon {
            width: 32px;
            height: 32px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #f5fbff;
            font-size: 1.18rem;
            font-weight: 900;
            background:
                radial-gradient(circle at 30% 24%, rgba(255,255,255,0.2), transparent 28%),
                linear-gradient(180deg, rgba(174, 184, 198, 0.94), rgba(67, 75, 88, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 8px 14px rgba(0,0,0,0.2);
            transition: transform 0.18s ease;
        }

        .expandable-action-panel[open] .expandable-action-summary-icon {
            transform: rotate(45deg);
        }

        .expandable-action-body {
            display: grid;
            gap: 18px;
            padding: 6px 22px 22px;
            border-top: 1px solid rgba(184, 230, 255, 0.12);
            background:
                linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0));
        }

        .expandable-action-group {
            display: grid;
            gap: 10px;
        }

        .reason-map-stage {
            display: grid;
            gap: 18px;
        }

        .reason-map-frame {
            position: relative;
            border-radius: 30px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                radial-gradient(circle at 16% 12%, rgba(255, 229, 162, 0.08), transparent 24%),
                radial-gradient(circle at 84% 10%, rgba(167, 239, 255, 0.08), transparent 24%),
                linear-gradient(180deg, rgba(9, 41, 84, 0.96), rgba(5, 24, 50, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 18px 30px rgba(0,0,0,0.24);
            padding: 24px 22px 22px;
        }

        .reason-map-plan {
            width: min(420px, 100%);
            margin: 0 auto 20px;
            padding: 16px 22px;
            border-radius: 24px;
            border: 1px solid rgba(184, 230, 255, 0.16);
            text-align: center;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background:
                linear-gradient(180deg, rgba(13, 58, 115, 0.88), rgba(6, 30, 63, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 14px 24px rgba(0,0,0,0.2);
        }

        .reason-map-script-wrap {
            position: relative;
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }

        .reason-map-script-wrap::after {
            content: "";
            position: absolute;
            top: calc(100% + 2px);
            left: 50%;
            width: 2px;
            height: 26px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, rgba(184, 230, 255, 0.54), rgba(184, 230, 255, 0));
        }

        .reason-map-requirement {
            appearance: none;
            position: relative;
            display: block;
            width: min(340px, 100%);
            margin: 0 auto 34px;
            padding: 13px 20px;
            border-radius: 20px;
            border: 1px solid rgba(255, 226, 145, 0.22);
            cursor: pointer;
            color: #fff6d4;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(1.08rem, 1.65vw, 1.3rem);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-align: center;
            text-transform: uppercase;
            background:
                linear-gradient(180deg, rgba(52, 76, 105, 0.92), rgba(23, 36, 56, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 12px 20px rgba(0,0,0,0.18);
            transform-style: preserve-3d;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .reason-map-requirement::after {
            content: "";
            position: absolute;
            top: calc(100% + 2px);
            left: 50%;
            width: 2px;
            height: 28px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, rgba(184, 230, 255, 0.54), rgba(184, 230, 255, 0));
        }

        .reason-map-requirement:hover,
        .reason-map-requirement:focus-visible {
            border-color: rgba(255, 226, 145, 0.38);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 16px 24px rgba(0,0,0,0.2),
                0 0 18px rgba(255, 226, 145, 0.12);
            outline: none;
            transform: translateY(-1px);
        }

        .reason-map-requirement.is-code {
            font-size: clamp(0.72rem, 1.08vw, 0.92rem);
            letter-spacing: 0.05em;
            line-height: 1.2;
            text-transform: none;
        }

        .reason-map-requirement.is-flipping {
            animation: reason-map-requirement-flip 0.62s cubic-bezier(.28,.88,.26,1.05);
        }

        @keyframes reason-map-requirement-flip {
            0% {
                transform: rotateY(0deg) translateY(-1px);
            }
            48% {
                transform: rotateY(92deg) translateY(-2px);
            }
            100% {
                transform: rotateY(360deg) translateY(-1px);
            }
        }

        .reason-map-node {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 999px;
            border: 1px solid rgba(184, 230, 255, 0.16);
            color: #f4fbff;
            background:
                linear-gradient(180deg, rgba(13, 59, 114, 0.9), rgba(6, 30, 61, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 12px 20px rgba(0,0,0,0.18);
        }

        .reason-map-node.is-script {
            width: 160px;
            height: 160px;
            padding: 20px;
            color: #fff4cc;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(1.08rem, 1.55vw, 1.28rem);
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .reason-map-branches {
            position: relative;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .reason-map-branches::before {
            content: "";
            position: absolute;
            top: 0;
            left: 12.5%;
            width: 75%;
            height: 2px;
            background: linear-gradient(90deg, rgba(184, 230, 255, 0), rgba(184, 230, 255, 0.6) 18%, rgba(184, 230, 255, 0.6) 82%, rgba(184, 230, 255, 0));
        }

        .reason-map-branch {
            position: relative;
            display: grid;
            justify-items: center;
            gap: 14px;
            padding-top: 24px;
        }

        .reason-map-branch::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            width: 2px;
            height: 24px;
            transform: translateX(-50%);
            background: linear-gradient(180deg, rgba(184, 230, 255, 0.64), rgba(184, 230, 255, 0));
        }

        .reason-map-node.is-case {
            width: min(100%, 176px);
            height: 176px;
            padding: 20px;
            cursor: pointer;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(0.84rem, 1.05vw, 0.96rem);
            font-weight: 900;
            letter-spacing: 0.05em;
            line-height: 1.14;
            text-transform: uppercase;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .reason-map-node.is-case span {
            max-width: 13ch;
            overflow-wrap: anywhere;
        }

        .reason-map-node.is-case:hover,
        .reason-map-node.is-case:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(255, 226, 145, 0.34);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.14),
                0 16px 24px rgba(0,0,0,0.2),
                0 0 18px rgba(140, 232, 255, 0.14);
            outline: none;
        }

        .reason-map-node.is-case.is-selected {
            border-color: rgba(255, 226, 145, 0.42);
            color: #fff8dd;
        }

        .reason-map-node.is-case.is-explicit {
            background:
                linear-gradient(180deg, rgba(15, 87, 141, 0.95), rgba(8, 42, 78, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
        }

        .reason-map-node.is-mindmap {
            width: 120px;
            height: 120px;
            padding: 18px;
            color: rgba(236, 246, 255, 0.9);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            line-height: 1.08;
            text-transform: uppercase;
        }

        .reason-map-branch-tail {
            display: grid;
            justify-items: center;
            gap: 12px;
            width: 100%;
        }

        .reason-map-layer-prompt {
            margin: 0;
            color: rgba(167, 239, 255, 0.78);
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            line-height: 1.25;
            text-align: center;
            text-transform: uppercase;
        }

        .reason-map-layer-prompt[hidden],
        .reason-map-layer-roller[hidden],
        .reason-map-preview[hidden] {
            display: none !important;
        }

        .reason-map-layer-roller {
            position: relative;
            display: grid;
            place-items: center;
            width: min(100%, 180px);
            min-height: 58px;
            padding: 7px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            border-radius: 18px;
            background:
                linear-gradient(180deg, rgba(11, 19, 33, 0.94), rgba(4, 10, 20, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                inset 0 -10px 18px rgba(0,0,0,0.22),
                0 10px 18px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .reason-map-layer-roller::before,
        .reason-map-layer-roller::after {
            content: "";
            position: absolute;
            left: 10px;
            right: 10px;
            height: 1px;
            background: rgba(255,255,255,0.14);
            z-index: 1;
        }

        .reason-map-layer-roller::before {
            top: 12px;
        }

        .reason-map-layer-roller::after {
            bottom: 12px;
        }

        .reason-map-layer-value {
            position: relative;
            z-index: 2;
            display: grid;
            place-items: center;
            width: 100%;
            min-height: 44px;
            border: 1px solid rgba(212, 220, 230, 0.22);
            border-radius: 14px;
            color: #f6f9fd;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-align: center;
            text-transform: uppercase;
            background:
                linear-gradient(180deg, rgba(96, 107, 122, 0.92), rgba(29, 35, 45, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 4px 0 rgba(16, 21, 28, 0.82);
            transition: transform 0.16s ease, opacity 0.16s ease, border-color 0.16s ease;
        }

        .reason-map-layer-roller.is-rolling .reason-map-layer-value {
            animation: reason-map-slot-flash 0.22s steps(2, end) infinite;
            border-color: rgba(167, 239, 255, 0.38);
        }

        .reason-map-layer-roller.is-settled .reason-map-layer-value {
            border-color: rgba(255, 226, 145, 0.38);
            color: #fff8df;
            transform: translateY(-1px);
        }

        .reason-map-layer-roller.is-nothing {
            border-color: rgba(171, 255, 211, 0.32);
            background:
                radial-gradient(circle at 50% 12%, rgba(171, 255, 211, 0.2), transparent 42%),
                linear-gradient(180deg, rgba(20, 84, 55, 0.88), rgba(6, 45, 29, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 0 22px rgba(86, 255, 173, 0.22),
                0 10px 18px rgba(0,0,0,0.18);
        }

        .reason-map-layer-roller.is-nothing::before,
        .reason-map-layer-roller.is-nothing::after {
            opacity: 0.28;
        }

        .reason-map-layer-roller.is-nothing .reason-map-layer-value {
            display: flex;
            align-items: center;
            justify-content: center;
            border-color: rgba(171, 255, 211, 0.34);
            color: #ecfff3;
            font-family: "Trebuchet MS", sans-serif;
            font-size: 0.96rem;
            font-weight: 900;
            letter-spacing: 0.03em;
            text-transform: none;
            background:
                linear-gradient(180deg, rgba(33, 135, 84, 0.96), rgba(13, 74, 48, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.16), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.2),
                0 4px 0 rgba(5, 40, 24, 0.72);
            transform: none;
        }

        .reason-map-layer-roller.is-nothing .reason-map-layer-value::before {
            content: "✓";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            margin-right: 7px;
            border-radius: 999px;
            color: #0b3e28;
            font-size: 0.72rem;
            font-weight: 900;
            background: #ecfff3;
            box-shadow: 0 0 12px rgba(236, 255, 243, 0.42);
        }

        .reason-map-layer-roller.is-nothing .reason-map-layer-value::before {
            content: "\2713";
        }

        @keyframes reason-map-slot-flash {
            0% {
                opacity: 0.52;
                transform: translateY(-2px);
            }
            100% {
                opacity: 1;
                transform: translateY(2px);
            }
        }

        .reason-map-preview {
            width: min(100%, 300px);
            padding: 14px 16px 15px;
            border-radius: 20px;
            border: 1px solid rgba(184, 230, 255, 0.16);
            background:
                linear-gradient(180deg, rgba(11, 50, 101, 0.74), rgba(5, 25, 50, 0.92)),
                linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 12px 20px rgba(0,0,0,0.16);
            text-align: center;
        }

        .reason-map-preview-label {
            margin: 0;
            color: rgba(167, 239, 255, 0.8);
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .reason-map-preview-title {
            margin: 0;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1.02rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .reason-map-preview-mode {
            margin: 8px 0 0;
            color: #f5fbff;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .reason-map-preview-reason {
            display: flex;
            align-items: center;
            min-height: 76px;
            margin: 12px 0 0;
            padding: 14px 16px;
            border: 1px solid rgba(212, 220, 230, 0.22);
            border-radius: 20px;
            color: #f6f9fd;
            font-size: clamp(0.88rem, 1.15vw, 1rem);
            font-weight: 800;
            letter-spacing: 0.05em;
            line-height: 1.2;
            background:
                linear-gradient(180deg, rgba(96, 107, 122, 0.92), rgba(29, 35, 45, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 14px 24px rgba(0,0,0,0.2),
                0 4px 0 rgba(16, 21, 28, 0.82);
            text-align: left;
            text-transform: none;
        }

        .reason-map-robot-panel {
            width: min(720px, 100%);
            margin: 28px auto 0;
            padding: 18px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            border-radius: 26px;
            background:
                radial-gradient(circle at 18% 18%, rgba(171, 255, 211, 0.12), transparent 30%),
                radial-gradient(circle at 82% 10%, rgba(167, 239, 255, 0.12), transparent 30%),
                linear-gradient(180deg, rgba(11, 50, 101, 0.76), rgba(5, 25, 50, 0.94));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.1),
                0 16px 26px rgba(0,0,0,0.2);
            animation: reason-map-robot-reveal 0.42s ease both;
        }

        .reason-map-robot-panel[hidden] {
            display: none !important;
        }

        .reason-map-robot-head {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            gap: 14px;
            align-items: center;
            margin-bottom: 16px;
        }

        .reason-map-robot-icon {
            position: relative;
            width: 68px;
            height: 58px;
            border: 2px solid rgba(171, 255, 211, 0.34);
            border-radius: 22px 22px 18px 18px;
            background:
                linear-gradient(180deg, rgba(33, 135, 84, 0.82), rgba(13, 74, 48, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.16), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 0 18px rgba(86, 255, 173, 0.2);
        }

        .reason-map-robot-icon::before {
            content: "";
            position: absolute;
            top: -16px;
            left: 50%;
            width: 2px;
            height: 14px;
            transform: translateX(-50%);
            background: rgba(171, 255, 211, 0.72);
            box-shadow: 0 -4px 0 2px rgba(171, 255, 211, 0.52);
        }

        .reason-map-robot-eye {
            position: absolute;
            top: 20px;
            width: 11px;
            height: 11px;
            border-radius: 999px;
            background: #ecfff3;
            box-shadow: 0 0 10px rgba(236, 255, 243, 0.6);
        }

        .reason-map-robot-eye.is-left {
            left: 18px;
        }

        .reason-map-robot-eye.is-right {
            right: 18px;
        }

        .reason-map-robot-mouth {
            position: absolute;
            left: 22px;
            right: 22px;
            bottom: 14px;
            height: 3px;
            border-radius: 999px;
            background: rgba(236, 255, 243, 0.72);
        }

        .reason-map-robot-kicker {
            margin: 0;
            color: rgba(167, 239, 255, 0.78);
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .reason-map-robot-title {
            margin: 3px 0 0;
            color: #f6f9fd;
            font-size: clamp(1rem, 1.6vw, 1.24rem);
            font-weight: 900;
            letter-spacing: 0.04em;
        }

        .reason-map-robot-sliders {
            display: grid;
            gap: 12px;
        }

        .reason-map-robot-slider {
            display: grid;
            grid-template-columns: minmax(92px, 0.45fr) minmax(0, 1fr) 54px;
            gap: 12px;
            align-items: center;
            padding: 12px 14px;
            border: 1px solid rgba(184, 230, 255, 0.14);
            border-radius: 18px;
            background: rgba(255,255,255,0.045);
        }

        .reason-map-robot-slider.is-disabled {
            opacity: 0.54;
            filter: blur(1.4px);
            pointer-events: none;
            user-select: none;
        }

        .reason-map-robot-slider-label,
        .reason-map-robot-value {
            color: #f6f9fd;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .reason-map-robot-value {
            color: var(--gold-soft);
            text-align: right;
        }

        .reason-map-robot-range {
            width: 100%;
            accent-color: #8cffc0;
        }

        @keyframes reason-map-robot-reveal {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .reason-map-actions {
            display: flex;
            justify-content: center;
            margin-top: 6px;
        }

        .reason-map-actions .choice-button {
            width: min(260px, 100%);
            min-height: auto;
            padding: 18px 22px;
            text-align: center;
        }

        .info-frame {
            border-radius: 28px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                linear-gradient(180deg, rgba(9, 40, 86, 0.94), rgba(5, 24, 50, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 16px 28px rgba(0,0,0,0.22);
            padding: 24px 24px 22px;
        }

        .info-title {
            margin: 0 0 12px;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1.14rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .info-copy {
            margin: 0;
            color: rgba(232, 245, 255, 0.86);
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .structured-chaos-stage {
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                radial-gradient(circle at 16% 12%, rgba(255, 229, 162, 0.14), transparent 24%),
                radial-gradient(circle at 84% 10%, rgba(167, 239, 255, 0.12), transparent 24%),
                linear-gradient(180deg, rgba(9, 43, 88, 0.96), rgba(5, 24, 52, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 18px 30px rgba(0,0,0,0.24);
            padding: 24px 20px 24px;
        }

        .structured-chaos-team {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .structured-chaos-tester {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 88px;
            min-height: 100px;
            border: 1px solid rgba(184, 230, 255, 0.16);
            border-radius: 22px;
            cursor: pointer;
            color: rgba(230, 244, 255, 0.72);
            background:
                linear-gradient(180deg, rgba(20, 78, 146, 0.86), rgba(8, 36, 74, 0.94));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.14),
                0 12px 20px rgba(0,0,0,0.18),
                0 3px 0 rgba(3, 21, 46, 0.7);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, color 0.18s ease;
        }

        .structured-chaos-tester:hover,
        .structured-chaos-tester:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(255, 229, 162, 0.4);
            color: #f7fbff;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 16px 24px rgba(0,0,0,0.22),
                0 3px 0 rgba(3, 21, 46, 0.72),
                0 0 18px rgba(167, 239, 255, 0.16);
            outline: none;
        }

        .structured-chaos-tester.is-active {
            color: #fff3c8;
            border-color: rgba(255, 229, 162, 0.48);
            background:
                linear-gradient(180deg, rgba(38, 116, 198, 0.9), rgba(11, 48, 96, 0.96));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.2),
                0 16px 24px rgba(0,0,0,0.22),
                0 3px 0 rgba(3, 21, 46, 0.72),
                0 0 18px rgba(255, 229, 162, 0.16);
        }

        .structured-chaos-tester.is-secondary.is-active {
            color: #e3f8ff;
            border-color: rgba(167, 239, 255, 0.48);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.2),
                0 16px 24px rgba(0,0,0,0.22),
                0 3px 0 rgba(3, 21, 46, 0.72),
                0 0 18px rgba(167, 239, 255, 0.18);
        }

        .structured-chaos-tester-icon {
            width: 34px;
            height: 46px;
        }

        .structured-chaos-tester-icon svg {
            display: block;
            width: 100%;
            height: 100%;
            stroke: currentColor;
            stroke-width: 3.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .structured-chaos-tester-count {
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .structured-chaos-figure {
            position: relative;
            width: 100%;
            min-height: 620px;
            border-radius: 28px;
            border: 1px solid rgba(184, 230, 255, 0.16);
            background:
                radial-gradient(circle at 50% 16%, rgba(255, 229, 162, 0.14), transparent 22%),
                radial-gradient(circle at 82% 12%, rgba(167, 239, 255, 0.08), transparent 24%),
                linear-gradient(180deg, rgba(7, 34, 72, 0.94), rgba(3, 20, 44, 0.96));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                inset 0 -18px 24px rgba(0, 0, 0, 0.16);
        }

        .structured-chaos-svg {
            display: block;
            width: 100%;
            height: auto;
        }

        .structured-chaos-axis,
        .structured-chaos-curve {
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .structured-chaos-axis {
            stroke: rgba(255, 229, 162, 0.32);
            stroke-width: 4;
        }

        .structured-chaos-axis.is-dashed {
            stroke-width: 4;
            stroke-dasharray: 12 12;
        }

        .structured-chaos-axis.is-primary {
            stroke: #ffd36c;
        }

        .structured-chaos-axis.is-secondary {
            stroke: #8ed8ff;
        }

        .structured-chaos-axis.is-hidden,
        .structured-chaos-indicator-cap.is-hidden {
            opacity: 0;
        }

        .structured-chaos-curve {
            stroke: rgba(167, 239, 255, 0.34);
            stroke-width: 6;
        }

        .structured-chaos-indicator-cap {
            stroke-width: 2;
            filter: drop-shadow(0 8px 14px rgba(0, 0, 0, 0.22));
        }

        .structured-chaos-indicator-cap.is-primary {
            fill: #ffd36c;
            stroke: rgba(255, 247, 220, 0.7);
        }

        .structured-chaos-indicator-cap.is-secondary {
            fill: #8ed8ff;
            stroke: rgba(235, 250, 255, 0.7);
        }

        .structured-chaos-label {
            position: absolute;
            color: rgba(255, 229, 162, 0.7);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-weight: 900;
            letter-spacing: 0.03em;
            text-transform: none;
            pointer-events: none;
        }

        .structured-chaos-label.is-y {
            top: 10%;
            left: 1.8%;
            font-size: clamp(1.8rem, 2.7vw, 2.95rem);
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        .structured-chaos-label.is-left {
            bottom: 11%;
            left: 5%;
            font-size: clamp(1.8rem, 3vw, 3rem);
        }

        .structured-chaos-label.is-right {
            right: 4%;
            bottom: 11%;
            font-size: clamp(1.8rem, 3vw, 3rem);
            text-align: right;
        }

        .structured-chaos-message {
            width: min(860px, calc(100% - 84px));
            min-height: 3.8em;
            margin: 6px auto 12px;
            color: #8ec8ff;
            font-size: clamp(0.98rem, 1.6vw, 1.16rem);
            font-weight: 700;
            line-height: 1.55;
            text-align: center;
            text-wrap: balance;
        }

        .structured-chaos-message-line {
            display: block;
        }

        .structured-chaos-message-line + .structured-chaos-message-line {
            margin-top: 4px;
        }

        .structured-chaos-slider-stack {
            display: grid;
            gap: 14px;
        }

        .structured-chaos-fader-shell {
            width: min(780px, calc(100% - 96px));
            margin: 0 auto;
            padding: 12px 18px 18px;
            border-radius: 20px;
            border: 1px solid rgba(184, 230, 255, 0.14);
            background:
                linear-gradient(180deg, rgba(15, 64, 118, 0.9), rgba(6, 30, 61, 0.96));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 16px 24px rgba(0,0,0,0.18);
        }

        .structured-chaos-fader-shell.is-secondary {
            border-color: rgba(167, 239, 255, 0.24);
        }

        .structured-chaos-fader-label {
            margin: 0 0 10px;
            color: rgba(255, 229, 162, 0.82);
            font-size: 0.76rem;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .structured-chaos-slider {
            width: 100%;
            height: 54px;
            margin: 0;
            appearance: none;
            background: transparent;
            cursor: ew-resize;
            --slider-thumb-top: #fff0ba;
            --slider-thumb-mid: #ffd36c;
            --slider-thumb-bottom: #9f7928;
            --slider-thumb-border: rgba(255, 245, 215, 0.6);
            --slider-thumb-glow: rgba(255, 211, 108, 0.16);
        }

        .structured-chaos-slider.is-secondary {
            --slider-thumb-top: #dff8ff;
            --slider-thumb-mid: #8ed8ff;
            --slider-thumb-bottom: #2f7daa;
            --slider-thumb-border: rgba(235, 250, 255, 0.68);
            --slider-thumb-glow: rgba(142, 216, 255, 0.16);
        }

        .structured-chaos-slider:focus-visible {
            outline: none;
        }

        .structured-chaos-slider::-webkit-slider-runnable-track {
            height: 10px;
            border-radius: 999px;
            border: 1px solid rgba(184, 230, 255, 0.14);
            background: linear-gradient(90deg, rgba(22, 74, 131, 0.96), rgba(77, 150, 208, 0.96), rgba(22, 74, 131, 0.96));
            box-shadow:
                inset 0 2px 5px rgba(0,0,0,0.2),
                0 1px 0 rgba(255,255,255,0.06);
        }

        .structured-chaos-slider::-webkit-slider-thumb {
            width: 34px;
            height: 54px;
            margin-top: -23px;
            border: 1px solid var(--slider-thumb-border);
            border-radius: 11px;
            appearance: none;
            background:
                linear-gradient(180deg, var(--slider-thumb-top), var(--slider-thumb-mid) 50%, var(--slider-thumb-bottom) 100%);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.28),
                0 10px 18px rgba(0,0,0,0.22),
                0 0 0 4px var(--slider-thumb-glow);
        }

        .structured-chaos-slider::-moz-range-track {
            height: 10px;
            border-radius: 999px;
            border: 1px solid rgba(184, 230, 255, 0.14);
            background: linear-gradient(90deg, rgba(22, 74, 131, 0.96), rgba(77, 150, 208, 0.96), rgba(22, 74, 131, 0.96));
            box-shadow:
                inset 0 2px 5px rgba(0,0,0,0.2),
                0 1px 0 rgba(255,255,255,0.06);
        }

        .structured-chaos-slider::-moz-range-thumb {
            width: 34px;
            height: 54px;
            border: 1px solid var(--slider-thumb-border);
            border-radius: 11px;
            background:
                linear-gradient(180deg, var(--slider-thumb-top), var(--slider-thumb-mid) 50%, var(--slider-thumb-bottom) 100%);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.28),
                0 10px 18px rgba(0,0,0,0.22),
                0 0 0 4px var(--slider-thumb-glow);
        }

        .structured-chaos-actions {
            display: flex;
            justify-content: center;
            margin-top: 22px;
        }

        .structured-chaos-actions .choice-button {
            min-height: auto;
            width: min(280px, 100%);
            padding: 18px 24px;
            border-radius: 22px;
            text-align: center;
        }

        .structured-chaos-actions .choice-copy {
            display: none;
        }

        .info-list {
            margin: 0;
            padding-left: 18px;
            color: rgba(240, 248, 255, 0.88);
            line-height: 1.75;
        }

        .info-list li + li {
            margin-top: 4px;
        }

        .info-table-wrap {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .info-table-wrap::-webkit-scrollbar {
            height: 11px;
        }

        .info-table-wrap::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(4, 22, 46, 0.76);
        }

        .info-table-wrap::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(255, 226, 145, 0.94), rgba(141, 231, 255, 0.94));
            border: 2px solid rgba(4, 22, 46, 0.92);
        }

        .info-table {
            width: 100%;
            min-width: 720px;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .info-table th,
        .info-table td {
            padding: 12px 14px;
            text-align: left;
            vertical-align: top;
        }

        .info-table thead th {
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .info-table tbody td {
            color: rgba(236, 246, 255, 0.9);
            font-size: 0.92rem;
            line-height: 1.55;
            background:
                linear-gradient(180deg, rgba(12, 56, 110, 0.72), rgba(6, 28, 58, 0.9)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            border-top: 1px solid rgba(180, 228, 255, 0.14);
            border-bottom: 1px solid rgba(12, 20, 34, 0.34);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 10px 18px rgba(0,0,0,0.14);
        }

        .info-table tbody td:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
            color: #fef5cb;
            font-weight: 800;
            letter-spacing: 0.03em;
        }

        .info-table tbody td:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .info-table tbody tr.is-clickable {
            cursor: pointer;
            touch-action: manipulation;
        }

        .info-table tbody tr.is-clickable td {
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease, border-color 0.18s ease;
        }

        .info-table tbody tr.is-clickable:hover td,
        .info-table tbody tr.is-clickable:focus-visible td {
            filter: brightness(1.08);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.1),
                0 14px 22px rgba(0,0,0,0.18),
                0 0 18px rgba(255, 226, 145, 0.12);
        }

        .info-table tbody tr.is-clickable:hover td:first-child,
        .info-table tbody tr.is-clickable:focus-visible td:first-child {
            color: #fff8dd;
        }

        .info-table tbody tr.is-clickable:focus-visible {
            outline: none;
        }

        .matrix-frame {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            border-radius: 28px;
            border: 1px solid rgba(184, 230, 255, 0.18);
            background:
                linear-gradient(180deg, rgba(8, 36, 74, 0.94), rgba(5, 22, 48, 0.97)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 16px 28px rgba(0,0,0,0.22);
            padding: 20px 20px 18px;
        }

        .matrix-note {
            margin: 0 0 14px;
            color: rgba(230, 244, 255, 0.82);
            font-size: 0.9rem;
            line-height: 1.55;
        }

        .matrix-mobile-hint {
            display: none;
            margin: -4px 0 12px;
            color: rgba(167, 239, 255, 0.82);
            font-size: 0.78rem;
            line-height: 1.45;
            letter-spacing: 0.03em;
        }

        .matrix-scroll {
            width: 100%;
            max-width: 100%;
            position: relative;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 4px;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            scrollbar-gutter: stable both-edges;
            touch-action: pan-x pan-y;
        }

        .matrix-scroll::-webkit-scrollbar {
            height: 12px;
        }

        .matrix-scroll::-webkit-scrollbar-track {
            border-radius: 999px;
            background: rgba(3, 20, 44, 0.74);
        }

        .matrix-scroll::-webkit-scrollbar-thumb {
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(255, 229, 148, 0.92), rgba(140, 232, 255, 0.92));
            border: 2px solid rgba(3, 20, 44, 0.9);
        }

        .matrix-table {
            width: 100%;
            min-width: 920px;
            border-collapse: separate;
            border-spacing: 8px;
        }

        .matrix-table th,
        .matrix-table td {
            vertical-align: middle;
        }

        .matrix-corner,
        .matrix-axis {
            padding: 12px 10px;
            border-radius: 18px;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.1), rgba(255,255,255,0.04)),
                rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.08), 0 10px 16px rgba(0,0,0,0.14);
            text-align: center;
        }

        .matrix-corner {
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.82rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .matrix-axis-title {
            display: block;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .matrix-axis-subtitle {
            display: block;
            margin-top: 6px;
            color: rgba(228, 242, 255, 0.74);
            font-size: 0.72rem;
            line-height: 1.4;
        }

        .matrix-row-label {
            min-width: 128px;
            padding: 14px 10px;
            border-radius: 18px;
            background:
                linear-gradient(180deg, rgba(8, 48, 95, 0.92), rgba(5, 25, 52, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            border: 1px solid rgba(180, 228, 255, 0.16);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.1), 0 10px 16px rgba(0,0,0,0.16);
            text-align: center;
        }

        .matrix-row-score {
            display: block;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1.08rem;
            font-weight: 900;
            line-height: 1;
        }

        .matrix-row-text {
            display: block;
            margin-top: 6px;
            color: rgba(231, 245, 255, 0.84);
            font-size: 0.76rem;
            line-height: 1.32;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .matrix-cell-button {
            width: 100%;
            min-height: 98px;
            border: 1px solid rgba(182, 229, 255, 0.18);
            border-radius: 22px;
            cursor: pointer;
            padding: 14px 10px;
            color: #f6fbff;
            background:
                linear-gradient(180deg, rgba(17, 74, 138, 0.95), rgba(7, 34, 72, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 14px 24px rgba(0,0,0,0.2),
                0 4px 0 rgba(2, 16, 38, 0.78);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .matrix-cell-button:hover,
        .matrix-cell-button:focus-visible {
            transform: translateY(-2px);
            border-color: rgba(255, 225, 146, 0.36);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.22),
                0 18px 28px rgba(0,0,0,0.24),
                0 4px 0 rgba(2, 16, 38, 0.78),
                0 0 18px rgba(140, 232, 255, 0.12);
            outline: none;
        }

        .matrix-cell-button:active {
            transform: translateY(4px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 8px 14px rgba(0,0,0,0.2),
                0 2px 0 rgba(2, 16, 38, 0.82);
        }

        .matrix-cell-tone {
            display: block;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.9rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .matrix-cell-score {
            display: block;
            margin-top: 7px;
            color: rgba(240, 248, 255, 0.92);
            font-size: 1.08rem;
            font-weight: 900;
            line-height: 1;
        }

        .ai-types-stage {
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            border: 1px solid rgba(186, 232, 255, 0.18);
            background:
                linear-gradient(180deg, rgba(236, 246, 255, 0.95), rgba(217, 235, 249, 0.9)),
                linear-gradient(135deg, rgba(255,255,255,0.28), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.5),
                0 18px 32px rgba(0,0,0,0.22);
            padding: 22px 20px 18px;
            color: #11264a;
        }

        .ai-types-stage::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(rgba(104, 168, 214, 0.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(104, 168, 214, 0.12) 1px, transparent 1px);
            background-size: 34px 34px;
            opacity: 0.6;
            pointer-events: none;
        }

        .ai-types-stage > * {
            position: relative;
            z-index: 1;
        }

        .ai-types-head {
            margin-bottom: 14px;
        }

        .ai-types-kicker {
            margin: 0 0 8px;
            color: rgba(26, 55, 100, 0.76);
            font-size: 0.76rem;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .ai-types-title {
            margin: 0;
            color: #08182f;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(1.55rem, 2.5vw, 2.45rem);
            font-weight: 900;
            line-height: 1.04;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .ai-types-copy {
            max-width: 70ch;
            margin: 10px 0 0;
            color: rgba(22, 44, 82, 0.84);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .ai-types-stack {
            display: grid;
            gap: 12px;
        }

        .ai-type-cluster {
            display: grid;
            grid-template-columns: minmax(148px, 164px) minmax(0, 1fr);
            align-items: center;
            gap: 20px;
        }

        .ai-type-core {
            position: relative;
            width: min(100%, 158px);
            aspect-ratio: 1 / 1;
            display: grid;
            place-items: center;
            margin: 0 auto;
            padding: 14px;
            border-radius: 999px;
            text-align: center;
            color: #ffffff;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.24),
                inset 0 -16px 24px rgba(0,0,0,0.12),
                0 18px 28px rgba(0,0,0,0.18);
        }

        .ai-type-core::after {
            content: "";
            position: absolute;
            inset: 9px;
            border-radius: inherit;
            border: 1px solid rgba(255,255,255,0.18);
            pointer-events: none;
        }

        .ai-type-core.is-traditional {
            background:
                radial-gradient(circle at 28% 20%, rgba(255,255,255,0.18), transparent 28%),
                linear-gradient(180deg, #6c67d7, #4d459f);
        }

        .ai-type-core.is-generative {
            background:
                radial-gradient(circle at 28% 20%, rgba(255,255,255,0.18), transparent 28%),
                linear-gradient(180deg, #48c38f, #29996f);
        }

        .ai-type-core.is-agentic {
            background:
                radial-gradient(circle at 28% 20%, rgba(255,255,255,0.18), transparent 28%),
                linear-gradient(180deg, #cf5cad, #a83c88);
        }

        .ai-type-core-small {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .ai-type-core-big {
            display: block;
            margin-top: 5px;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: clamp(1.72rem, 2.35vw, 2.6rem);
            font-weight: 900;
            line-height: 1.02;
            letter-spacing: 0.01em;
            text-transform: uppercase;
        }

        .ai-type-items {
            display: grid;
            gap: 10px;
        }

        .ai-type-item {
            position: relative;
            display: grid;
            grid-template-columns: 52px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
        }

        .ai-type-item::before {
            content: "";
            position: absolute;
            top: 50%;
            left: -32px;
            width: 40px;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(10, 30, 63, 0.88), rgba(10, 30, 63, 0.22));
            transform: translateY(-50%);
        }

        .ai-type-item::after {
            content: "";
            position: absolute;
            top: calc(50% - 4px);
            left: -10px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: currentColor;
            box-shadow: 0 0 0 4px rgba(255,255,255,0.62);
        }

        .ai-type-cluster.is-traditional {
            color: #7067da;
        }

        .ai-type-cluster.is-generative {
            color: #33b784;
        }

        .ai-type-cluster.is-agentic {
            color: #d05fb0;
        }

        .ai-type-icon {
            position: relative;
            z-index: 1;
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            color: #ffffff;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.76rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            background:
                radial-gradient(circle at 28% 20%, rgba(255,255,255,0.24), transparent 28%),
                linear-gradient(180deg, currentColor, color-mix(in srgb, currentColor 74%, #2d245d 26%));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.24),
                0 12px 20px rgba(0,0,0,0.16);
        }

        .ai-type-item-body {
            padding: 10px 12px 10px 14px;
            border-radius: 18px;
            border: 1px solid rgba(18, 47, 90, 0.1);
            background: rgba(255,255,255,0.76);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.44),
                0 12px 18px rgba(24, 52, 95, 0.08);
        }

        .ai-type-item-head {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .ai-type-number {
            flex: 0 0 auto;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            background: rgba(14, 33, 66, 0.92);
            color: #f7fbff;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.76rem;
            font-weight: 900;
            line-height: 1;
        }

        .ai-type-item-title {
            margin: 0;
            color: #122548;
            font-size: 0.94rem;
            font-weight: 900;
            line-height: 1.28;
        }

        .ai-type-item-copy {
            position: relative;
            margin: 0;
            padding-left: 12px;
            color: rgba(24, 47, 85, 0.86);
            font-size: 0.84rem;
            line-height: 1.42;
        }

        .ai-type-item-copy::before {
            content: "•";
            position: absolute;
            left: 0;
            top: 0;
            color: rgba(17, 37, 72, 0.82);
            font-size: 1rem;
            line-height: 1.35;
        }

        .dtap-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .dtap-stack {
            display: grid;
            gap: 16px;
        }

        .dtap-topbar {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .dtap-stage-shell {
            width: min(248px, 100%);
            padding: 10px 12px 8px;
            border-radius: 16px;
            border: 1px solid rgba(180, 228, 255, 0.16);
            background:
                linear-gradient(180deg, rgba(9, 40, 86, 0.88), rgba(5, 24, 50, 0.94)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 12px 22px rgba(0,0,0,0.18);
        }

        .dtap-stage-label {
            margin: 0 0 7px;
            color: rgba(236, 246, 255, 0.82);
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .dtap-stage-range {
            width: 100%;
            height: 18px;
            appearance: none;
            -webkit-appearance: none;
            background: transparent;
            cursor: ew-resize;
        }

        .dtap-stage-range::-webkit-slider-runnable-track {
            height: 6px;
            border-radius: 999px;
            background:
                linear-gradient(90deg, rgba(255, 229, 162, 0.92), rgba(126, 217, 255, 0.88));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 3px 7px rgba(0,0,0,0.18);
        }

        .dtap-stage-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 10px;
            height: 20px;
            margin-top: -7px;
            border-radius: 4px;
            border: 1px solid rgba(250, 241, 220, 0.52);
            background:
                linear-gradient(180deg, rgba(255,255,255,0.92), rgba(196, 212, 228, 0.92));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.36),
                0 5px 10px rgba(0,0,0,0.24);
        }

        .dtap-stage-range::-moz-range-track {
            height: 6px;
            border: 0;
            border-radius: 999px;
            background:
                linear-gradient(90deg, rgba(255, 229, 162, 0.92), rgba(126, 217, 255, 0.88));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 3px 7px rgba(0,0,0,0.18);
        }

        .dtap-stage-range::-moz-range-thumb {
            width: 10px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(250, 241, 220, 0.52);
            background:
                linear-gradient(180deg, rgba(255,255,255,0.92), rgba(196, 212, 228, 0.92));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.36),
                0 5px 10px rgba(0,0,0,0.24);
        }

        .dtap-stage-ticks {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            align-items: center;
            margin-top: 5px;
            color: rgba(210, 232, 250, 0.6);
            font-size: 0.58rem;
            letter-spacing: 0.08em;
        }

        .dtap-stage-ticks span {
            text-align: center;
        }

        .dtap-stage-panel {
            display: grid;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 24px;
            border: 1px solid rgba(180, 228, 255, 0.16);
            background:
                linear-gradient(180deg, rgba(10, 45, 92, 0.92), rgba(4, 22, 48, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.1),
                0 14px 24px rgba(0,0,0,0.18);
        }

        .dtap-stage-copy {
            margin: 0;
            color: rgba(236, 246, 255, 0.9);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .dtap-stage-emphasis {
            color: var(--gold-soft);
            font-weight: 900;
        }

        .dtap-expansion-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .dtap-expansion-card,
        .dtap-expansion-spacer {
            min-height: 92px;
            border-radius: 22px;
        }

        .dtap-expansion-card {
            padding: 14px 14px 12px;
            border: 1px solid rgba(180, 228, 255, 0.16);
            background:
                linear-gradient(180deg, rgba(13, 57, 114, 0.9), rgba(6, 28, 58, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 10px 18px rgba(0,0,0,0.16);
        }

        .dtap-expansion-title {
            margin: 0 0 8px;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.84rem;
            font-weight: 900;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .dtap-expansion-copy {
            margin: 0;
            color: rgba(231, 245, 255, 0.86);
            font-size: 0.84rem;
            line-height: 1.5;
        }

        .dtap-expansion-spacer {
            visibility: hidden;
            pointer-events: none;
        }

        .dtap-column {
            display: grid;
            gap: 14px;
            grid-template-rows: auto 1fr;
            padding: 18px 16px 16px;
            border-radius: 24px;
            border: 1px solid rgba(180, 228, 255, 0.16);
            background:
                linear-gradient(180deg, rgba(9, 40, 86, 0.94), rgba(5, 24, 50, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 16px 28px rgba(0,0,0,0.22);
        }

        .dtap-head {
            display: grid;
            align-content: start;
            gap: 4px;
            min-height: 56px;
        }

        .dtap-version {
            margin: 0;
            color: var(--gold-soft);
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 1.06rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1.04;
        }

        .dtap-environment {
            margin: 0;
            color: rgba(232, 245, 255, 0.84);
            font-size: 0.9rem;
            line-height: 1.45;
            letter-spacing: 0.04em;
        }

        .dtap-form {
            display: grid;
            align-content: start;
            gap: 10px;
        }

        .dtap-input {
            width: 100%;
            height: 48px;
            padding: 12px 14px;
            border: 1px solid rgba(179, 227, 255, 0.18);
            border-radius: 16px;
            color: #f3f9ff;
            font-size: 0.9rem;
            background:
                linear-gradient(180deg, rgba(10, 44, 90, 0.92), rgba(4, 22, 48, 0.96)),
                linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 10px 18px rgba(0,0,0,0.14);
        }

        .dtap-input.is-seeded {
            color: rgba(225, 239, 255, 0.56);
        }

        .dtap-input:focus {
            outline: none;
            border-color: rgba(255, 225, 146, 0.34);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 0 0 3px rgba(167, 239, 255, 0.1),
                0 10px 18px rgba(0,0,0,0.16);
        }

        .dtap-input-spacer {
            width: 100%;
            height: 48px;
            border-radius: 16px;
            visibility: hidden;
            pointer-events: none;
        }

        .dtap-submit {
            width: 100%;
            height: 48px;
            border: 1px solid var(--dtap-submit-border, rgba(182, 229, 255, 0.18));
            border-radius: 18px;
            cursor: pointer;
            color: #eef8ff;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.96rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background:
                linear-gradient(180deg, var(--dtap-submit-top, rgba(17, 74, 138, 0.95)), var(--dtap-submit-bottom, rgba(7, 34, 72, 0.98))),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 14px 24px rgba(0,0,0,0.2),
                0 4px 0 var(--dtap-submit-edge, rgba(2, 16, 38, 0.78));
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .dtap-submit:hover,
        .dtap-submit:focus-visible {
            transform: translateY(-2px);
            border-color: var(--dtap-submit-border-hover, rgba(255, 225, 146, 0.36));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.22),
                0 18px 28px rgba(0,0,0,0.24),
                0 4px 0 var(--dtap-submit-edge, rgba(2, 16, 38, 0.78)),
                0 0 18px var(--dtap-submit-glow, rgba(140, 232, 255, 0.12));
            outline: none;
        }

        .dtap-submit:active {
            transform: translateY(4px);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.16),
                0 8px 14px rgba(0,0,0,0.2),
                0 2px 0 rgba(2, 16, 38, 0.82);
        }

        .dive-deeper-shell {
            justify-self: end;
            width: auto;
            max-width: min(250px, 100%);
            margin-top: 8px;
        }

        .dive-deeper-panel {
            border-radius: 18px;
            border: 1px solid rgba(231, 196, 151, 0.18);
            background:
                linear-gradient(180deg, rgba(42, 36, 33, 0.96), rgba(19, 16, 15, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.08),
                0 12px 20px rgba(0,0,0,0.24);
            overflow: hidden;
        }

        .dive-deeper-summary {
            list-style: none;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            min-width: 38px;
            padding: 7px;
            cursor: pointer;
            color: #dfc7a3;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.68rem;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            user-select: none;
        }

        .dive-deeper-summary::-webkit-details-marker {
            display: none;
        }

        .dive-deeper-summary-text {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            padding: 5px 8px 6px;
            border-radius: 999px;
            border: 1px solid rgba(239, 221, 190, 0.34);
            background:
                linear-gradient(180deg, rgba(75, 66, 58, 0.98), rgba(35, 30, 27, 0.99)),
                linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            color: #fff4dc;
            white-space: nowrap;
            font-size: 0.62rem;
            line-height: 1;
            letter-spacing: 0.16em;
            opacity: 0;
            transform: translateY(-4px);
            pointer-events: none;
            transition: opacity 0.18s ease, transform 0.18s ease;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.06),
                0 8px 14px rgba(0,0,0,0.2);
        }

        .dive-deeper-summary:hover .dive-deeper-summary-text,
        .dive-deeper-summary:focus-visible .dive-deeper-summary-text {
            opacity: 1;
            transform: translateY(0);
        }

        .dive-deeper-summary-icon {
            width: 22px;
            height: 22px;
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #f6efe4;
            background:
                radial-gradient(circle at 28% 22%, rgba(255,255,255,0.2), transparent 28%),
                linear-gradient(180deg, rgba(207, 160, 93, 0.94), rgba(95, 62, 28, 0.98));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.18),
                0 6px 10px rgba(0,0,0,0.22);
            transition: transform 0.18s ease;
        }

        .dive-deeper-panel[open] .dive-deeper-summary-icon {
            transform: rotate(90deg);
        }

        .dive-deeper-body {
            min-width: 214px;
            padding: 0 10px 10px;
            display: grid;
            gap: 6px;
            background:
                linear-gradient(180deg, rgba(58, 49, 43, 0.28), rgba(19, 16, 15, 0));
        }

        .dive-deeper-list {
            display: grid;
            gap: 6px;
        }

        .dive-deeper-link {
            display: grid;
            grid-template-columns: 24px minmax(0, 1fr);
            align-items: start;
            gap: 7px;
            color: rgba(249, 240, 226, 0.98);
            text-decoration: none;
            font-size: 0.76rem;
            line-height: 1.4;
        }

        .dive-deeper-link:hover,
        .dive-deeper-link:focus-visible {
            color: #fff0cb;
            outline: none;
        }

        .dive-deeper-link-index {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #f6fbff;
            font-family: "Bahnschrift SemiCondensed", "Trebuchet MS", sans-serif;
            font-size: 0.74rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            background:
                linear-gradient(180deg, rgba(149, 110, 66, 0.96), rgba(65, 45, 24, 0.98)),
                linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0));
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.14),
                0 6px 10px rgba(0,0,0,0.14);
        }

        .dive-deeper-link-text {
            min-width: 0;
            color: inherit;
            text-shadow: 0 1px 0 rgba(0,0,0,0.24);
        }

        .dive-deeper-empty {
            margin: 0;
            color: rgba(245, 236, 222, 0.8);
            font-size: 0.72rem;
            line-height: 1.5;
        }

        @media (max-width: 900px) {
            .page-shell {
                width: min(100% - 18px, 100%);
                padding: 18px 0 24px;
            }

            .overview-card {
                padding: 20px 18px 22px;
                border-radius: 24px;
            }

            .overview-header {
                flex-wrap: wrap;
                align-items: flex-start;
            }

            .overview-header-actions {
                width: auto;
                margin-left: auto;
                justify-content: flex-end;
            }

            .edit-mode-toggle {
                display: none;
            }

            .narration-button,
            .back-button {
                width: 48px;
                height: 48px;
                border-radius: 16px;
            }

            .narration-control {
                gap: 7px;
                padding: 7px;
                border-radius: 18px;
            }

            .autoplay-toggle {
                min-width: 72px;
                min-height: 48px;
                padding: 6px 8px;
                border-radius: 14px;
            }

            .autoplay-toggle-label {
                font-size: 0.5rem;
                letter-spacing: 0.1em;
            }

            .edit-mode-toggle-label {
                font-size: 0.74rem;
            }

            .choice-grid,
            .choice-grid.is-triple {
                grid-template-columns: 1fr;
            }

            .decision-status-row,
            .decision-intent-grid {
                grid-template-columns: 1fr;
            }

            .decision-intent-shell {
                padding: 14px;
                border-radius: 22px;
            }

            .choice-button {
                min-height: 146px;
                padding: 22px 18px;
                border-radius: 22px;
            }
            .choice-button.is-compact {
                min-height: 92px;
                padding: 16px 14px;
            }
            .choice-button.is-small {
                min-height: 68px;
                padding: 12px 14px;
                border-radius: 18px;
            }
            .choice-button.is-small .choice-label {
                font-size: 0.84rem;
                line-height: 1.22;
            }

            .choice-copy {
                max-width: none;
            }

            .knowledge-overlap-shell {
                margin-top: clamp(44px, 12vw, 72px);
            }

            .knowledge-overlap-panel {
                border-radius: 22px;
            }

            .knowledge-overlap-summary {
                min-height: 60px;
                padding: 16px 16px;
                gap: 14px;
            }

            .knowledge-overlap-summary-kicker {
                font-size: 0.64rem;
            }

            .knowledge-overlap-summary-title {
                font-size: 0.9rem;
                line-height: 1.12;
            }

            .knowledge-overlap-summary-icon {
                width: 28px;
                height: 28px;
                font-size: 1rem;
            }

            .knowledge-overlap-body {
                grid-template-columns: 1fr;
                gap: 18px;
                padding: 4px 16px 18px;
            }

            .knowledge-overlap-diagram {
                width: min(100%, 272px);
                min-height: 228px;
            }

            .knowledge-overlap-ring {
                width: 45%;
                padding: 14px;
                border-width: 3px;
            }

            .knowledge-overlap-ring-label {
                font-size: 0.68rem;
                letter-spacing: 0.03em;
            }

            .knowledge-overlap-note {
                padding: 14px 14px 15px;
                border-radius: 18px;
                font-size: 0.88rem;
            }

            .knowledge-overlap-note-term {
                font-size: 0.94rem;
            }

            .expandable-action-shell {
                margin-top: clamp(44px, 12vw, 72px);
            }

            .expandable-action-panel {
                border-radius: 22px;
            }

            .expandable-action-summary {
                min-height: 60px;
                padding: 16px 16px;
                gap: 14px;
            }

            .expandable-action-summary-kicker {
                font-size: 0.64rem;
            }

            .expandable-action-summary-title {
                font-size: 0.9rem;
                line-height: 1.12;
            }

            .expandable-action-summary-icon {
                width: 28px;
                height: 28px;
                font-size: 1rem;
            }

            .expandable-action-body {
                padding: 4px 16px 18px;
                gap: 16px;
            }

            .reason-map-frame {
                padding: 18px 10px 18px;
                border-radius: 22px;
            }

            .reason-map-plan {
                padding: 14px 16px;
                font-size: clamp(1.22rem, 5vw, 1.56rem);
                border-radius: 20px;
            }

            .reason-map-script-wrap {
                margin-bottom: 24px;
            }

            .reason-map-script-wrap::after {
                height: 20px;
            }

            .reason-map-requirement {
                width: min(260px, 100%);
                margin-bottom: 28px;
                padding: 11px 16px;
                border-radius: 18px;
                font-size: 0.94rem;
            }

            .reason-map-requirement.is-code {
                font-size: 0.62rem;
                letter-spacing: 0.03em;
            }

            .reason-map-requirement::after {
                height: 22px;
            }

            .reason-map-node.is-script {
                width: 126px;
                height: 126px;
                font-size: 0.9rem;
                padding: 16px;
            }

            .reason-map-branches {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .reason-map-branches::before {
                left: 25%;
                width: 50%;
            }

            .reason-map-branch {
                gap: 10px;
                padding-top: 18px;
            }

            .reason-map-branch::before {
                height: 18px;
            }

            .reason-map-node.is-case {
                width: min(100%, 126px);
                height: 126px;
                padding: 14px;
                font-size: 0.58rem;
                letter-spacing: 0.04em;
                line-height: 1.16;
            }

            .reason-map-node.is-mindmap {
                width: 86px;
                height: 86px;
                padding: 12px;
                font-size: 0.64rem;
            }

            .reason-map-layer-prompt {
                max-width: 150px;
                font-size: 0.58rem;
                letter-spacing: 0.08em;
            }

            .reason-map-layer-roller {
                width: min(100%, 150px);
                min-height: 42px;
                border-radius: 14px;
                padding: 6px;
            }

            .reason-map-layer-value {
                min-height: 34px;
                border-radius: 11px;
                font-size: 0.72rem;
            }

            .reason-map-preview {
                width: min(100%, 156px);
                padding: 12px 10px 13px;
                border-radius: 16px;
            }

            .reason-map-preview-label {
                font-size: 0.58rem;
            }

            .reason-map-preview-title {
                font-size: 0.82rem;
            }

            .reason-map-preview-mode {
                font-size: 0.78rem;
            }

            .reason-map-preview-reason {
                font-size: 0.68rem;
                line-height: 1.35;
                min-height: 64px;
                padding: 12px 10px;
                border-radius: 14px;
            }

            .reason-map-robot-panel {
                margin-top: 22px;
                padding: 15px 12px;
                border-radius: 20px;
            }

            .reason-map-robot-head {
                grid-template-columns: 1fr;
                justify-items: center;
                text-align: center;
            }

            .reason-map-robot-icon {
                width: 60px;
                height: 52px;
            }

            .reason-map-robot-slider {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 11px 12px;
            }

            .reason-map-robot-value {
                text-align: left;
            }

            .structured-chaos-stage {
                padding: 18px 10px 18px;
                border-radius: 22px;
            }

            .structured-chaos-team {
                gap: 10px;
                margin-bottom: 14px;
            }

            .structured-chaos-tester {
                width: 74px;
                min-height: 90px;
                border-radius: 18px;
            }

            .structured-chaos-figure {
                min-height: 420px;
            }

            .structured-chaos-label.is-y {
                left: 0.6%;
                top: 14%;
                font-size: clamp(1.15rem, 4vw, 1.65rem);
            }

            .structured-chaos-label.is-left,
            .structured-chaos-label.is-right {
                bottom: 12%;
                font-size: clamp(1.15rem, 4.7vw, 1.8rem);
            }

            .structured-chaos-label.is-right {
                right: 1%;
            }

            .structured-chaos-message {
                width: min(100%, calc(100% - 20px));
                min-height: 4.3em;
                margin: 4px auto 10px;
                font-size: 0.98rem;
                line-height: 1.5;
            }

            .structured-chaos-slider-stack {
                gap: 10px;
            }

            .structured-chaos-fader-shell {
                width: min(100%, calc(100% - 16px));
                padding: 10px 12px 14px;
            }

            .structured-chaos-slider {
                height: 48px;
            }

            .structured-chaos-slider::-webkit-slider-thumb {
                width: 28px;
                height: 48px;
                margin-top: -20px;
            }

            .structured-chaos-slider::-moz-range-thumb {
                width: 28px;
                height: 48px;
            }

            .dtap-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .dtap-topbar {
                justify-content: stretch;
            }

            .dtap-stage-shell {
                width: 100%;
                padding: 9px 11px 8px;
            }

            .dtap-stage-panel {
                padding: 14px 14px 12px;
                border-radius: 22px;
            }

            .dtap-stage-copy {
                font-size: 0.88rem;
            }

            .dtap-expansion-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .dtap-expansion-card,
            .dtap-expansion-spacer {
                min-height: 0;
            }

            .dtap-expansion-spacer {
                display: none;
            }

            .dtap-column {
                padding: 16px 14px 14px;
                border-radius: 22px;
            }

            .dtap-head {
                min-height: 0;
            }

            .dtap-input {
                width: 100%;
                height: 48px;
            }

            .dtap-input-spacer {
                height: 48px;
            }

            .dtap-submit {
                width: 100%;
                height: 48px;
            }

            .dive-deeper-shell {
                width: auto;
                max-width: min(220px, 100%);
            }

            .dive-deeper-summary {
                min-height: 34px;
                min-width: 34px;
                padding: 6px;
                font-size: 0.62rem;
            }

            .dive-deeper-body {
                min-width: 188px;
                padding: 0 9px 9px;
            }

            .dive-deeper-link {
                font-size: 0.72rem;
            }

            .matrix-frame {
                padding: 16px 14px 14px;
                border-radius: 22px;
            }

            .matrix-note {
                font-size: 0.82rem;
            }

            .matrix-mobile-hint {
                display: block;
            }

            .matrix-scroll {
                margin: 0 -4px;
                padding: 0 4px 10px;
            }

            .matrix-table {
                min-width: 640px;
                border-spacing: 4px;
            }

            .info-table {
                min-width: 620px;
            }

            .matrix-corner,
            .matrix-axis {
                padding: 8px 6px;
                border-radius: 14px;
            }

            .matrix-corner,
            .matrix-axis-title {
                font-size: 0.68rem;
                letter-spacing: 0.05em;
            }

            .matrix-axis-subtitle {
                margin-top: 4px;
                font-size: 0.62rem;
            }

            .matrix-row-label {
                min-width: 86px;
                padding: 10px 6px;
                border-radius: 14px;
            }

            .matrix-row-score {
                font-size: 0.92rem;
            }

            .matrix-row-text {
                margin-top: 4px;
                font-size: 0.62rem;
                letter-spacing: 0.04em;
            }

            .matrix-cell-button {
                min-height: 72px;
                padding: 10px 6px;
                border-radius: 16px;
            }

            .matrix-cell-tone {
                font-size: 0.66rem;
                letter-spacing: 0.05em;
            }

            .matrix-cell-score {
                margin-top: 5px;
                font-size: 0.88rem;
            }

            .edit-builder {
                grid-template-columns: 1fr;
            }

            .edit-builder-submit {
                width: 100%;
            }

            .edit-panel-head {
                flex-direction: column;
            }

            .ai-types-stage {
                padding: 18px 14px 16px;
                border-radius: 24px;
            }

            .ai-types-stage::before {
                background-size: 24px 24px;
            }

            .ai-types-head {
                margin-bottom: 12px;
            }

            .ai-types-title {
                font-size: clamp(1.38rem, 6vw, 2.05rem);
                line-height: 1.06;
            }

            .ai-types-copy {
                font-size: 0.84rem;
            }

            .ai-type-cluster {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .ai-type-core {
                width: min(100%, 136px);
            }

            .ai-type-items {
                gap: 8px;
            }

            .ai-type-item {
                grid-template-columns: 44px minmax(0, 1fr);
                gap: 8px;
            }

            .ai-type-item::before {
                left: -10px;
                width: 22px;
            }

            .ai-type-item::after {
                left: 8px;
            }

            .ai-type-icon {
                width: 44px;
                height: 44px;
                border-radius: 16px;
                font-size: 0.68rem;
            }

            .ai-type-item-body {
                padding: 10px 10px 10px 12px;
                border-radius: 18px;
            }

            .ai-type-item-head {
                gap: 8px;
                margin-bottom: 5px;
            }

            .ai-type-number {
                width: 22px;
                height: 22px;
                border-radius: 7px;
                font-size: 0.72rem;
            }

            .ai-type-item-title {
                font-size: 0.88rem;
            }

            .ai-type-item-copy {
                font-size: 0.78rem;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <section class="overview-card" aria-live="polite">
            <div class="overview-header">
                <div class="overview-title-wrap">
                    <p class="overview-kicker">Knowledge map</p>
                    <h1 id="viewTitle" class="overview-title">Test overview</h1>
                    <p id="viewBreadcrumb" class="overview-breadcrumb">Choose a direction to continue.</p>
                </div>
                <div class="overview-header-actions">
                    <button id="editModeToggle" class="edit-mode-toggle" type="button" aria-pressed="false" aria-label="Toggle edit mode">
                        <span id="editModeToggleLabel" class="edit-mode-toggle-label">View modus</span>
                        <span class="edit-mode-toggle-track" aria-hidden="true">
                            <span class="edit-mode-toggle-knob"></span>
                        </span>
                    </button>
                    <div class="narration-control">
                        <button id="autoplayToggle" class="autoplay-toggle" type="button" aria-pressed="false" aria-label="Autoplay narration off">
                            <span class="autoplay-toggle-label">Autoplay</span>
                            <span class="autoplay-toggle-track" aria-hidden="true">
                                <span class="autoplay-toggle-knob"></span>
                            </span>
                        </button>
                        <button id="narrationButton" class="narration-button" type="button" aria-pressed="false" aria-label="Page narration unavailable" disabled>
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path fill="currentColor" d="M4.5 9.3h3.2l4-3.1c.55-.42 1.33-.03 1.33.66v10.24c0 .69-.78 1.08-1.33.66l-4-3.08H4.5c-.47 0-.85-.38-.85-.85v-3.7c0-.47.38-.85.85-.85Z"/>
                                <path fill="currentColor" d="M16.2 9.2c0-.66.73-1.06 1.3-.7l4.1 2.64a.83.83 0 0 1 0 1.4l-4.1 2.64a.83.83 0 0 1-1.3-.7V9.2Z"/>
                            </svg>
                        </button>
                    </div>
                    <button id="backButton" class="back-button" type="button" hidden aria-label="Go back one level">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M14.72 5.47a.85.85 0 0 1 0 1.2L10.25 11.1h8.11a.85.85 0 1 1 0 1.7h-8.1l4.46 4.43a.85.85 0 1 1-1.2 1.2l-5.9-5.88a.85.85 0 0 1 0-1.2l5.9-5.88a.85.85 0 0 1 1.2 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="overviewContent" class="overview-content"></div>
        </section>
    </main>

    <script>
        const backButton = document.getElementById('backButton');
        const editModeToggle = document.getElementById('editModeToggle');
        const editModeToggleLabel = document.getElementById('editModeToggleLabel');
        const autoplayToggle = document.getElementById('autoplayToggle');
        const narrationButton = document.getElementById('narrationButton');
        const viewTitle = document.getElementById('viewTitle');
        const viewBreadcrumb = document.getElementById('viewBreadcrumb');
        const overviewContent = document.getElementById('overviewContent');
        const EDIT_MODE_STORAGE_KEY = 'testoverview-edit-mode-v1';
        const CUSTOM_OVERVIEW_STORAGE_KEY = 'testoverview-custom-overview-v1';
        const DTAP_THEME_STORAGE_KEY = 'testoverview-dtap-theme-v1';
        const DTAP_STAGE_STORAGE_KEY = 'testoverview-dtap-stage-v1';
        const NARRATION_AUTOPLAY_STORAGE_KEY = 'testoverview-narration-autoplay-v1';
        const supportsSpeechSynthesis = typeof window !== 'undefined'
            && 'speechSynthesis' in window
            && typeof window.SpeechSynthesisUtterance === 'function';

        let activeNarrationText = '';
        let narrationVoicesPromise = null;
        let narrationPlaybackToken = 0;
        let narrationState = 'idle';
        let continueNarrationPlayback = null;

        const defaultPageTheme = {
            bgTop: '#081d3d',
            bgMid: '#0d3b77',
            bgBottom: '#5ea6d3'
        };

        const dtapThemes = {
            development: {
                submitTop: '#3f9fff',
                submitBottom: '#144f9d',
                submitBorder: 'rgba(173, 222, 255, 0.42)',
                submitBorderHover: 'rgba(222, 244, 255, 0.62)',
                submitEdge: 'rgba(7, 34, 72, 0.84)',
                submitGlow: 'rgba(109, 200, 255, 0.22)',
                bgTop: '#08234a',
                bgMid: '#1b5fb7',
                bgBottom: '#7fd4ff'
            },
            test: {
                submitTop: '#32cfb7',
                submitBottom: '#12796f',
                submitBorder: 'rgba(180, 255, 236, 0.34)',
                submitBorderHover: 'rgba(214, 255, 246, 0.58)',
                submitEdge: 'rgba(8, 57, 52, 0.82)',
                submitGlow: 'rgba(99, 255, 218, 0.2)',
                bgTop: '#083b41',
                bgMid: '#169388',
                bgBottom: '#8ff4e6'
            },
            acceptance: {
                submitTop: '#f0bf58',
                submitBottom: '#a56613',
                submitBorder: 'rgba(255, 226, 171, 0.34)',
                submitBorderHover: 'rgba(255, 241, 212, 0.58)',
                submitEdge: 'rgba(90, 53, 9, 0.82)',
                submitGlow: 'rgba(255, 208, 105, 0.2)',
                bgTop: '#4a2f0d',
                bgMid: '#c58b29',
                bgBottom: '#ffd992'
            },
            production: {
                submitTop: '#ec678a',
                submitBottom: '#96274f',
                submitBorder: 'rgba(255, 190, 212, 0.34)',
                submitBorderHover: 'rgba(255, 225, 236, 0.58)',
                submitEdge: 'rgba(84, 17, 43, 0.84)',
                submitGlow: 'rgba(255, 135, 181, 0.2)',
                bgTop: '#49112b',
                bgMid: '#b43d6a',
                bgBottom: '#ff9cbe'
            }
        };

        const requirementCategories = [
            { slug: 'business-rule', label: 'Business rule requirement' },
            { slug: 'functional', label: 'Functional requirement' },
            { slug: 'non-functional', label: 'Non-functional requirement' },
            { slug: 'stakeholder', label: 'Stakeholder requirement' },
            { slug: 'user', label: 'User requirement' },
            { slug: 'system', label: 'System requirement' },
            { slug: 'interface', label: 'Interface requirement' },
            { slug: 'data', label: 'Data requirement' },
            { slug: 'constraint', label: 'Constraint requirement' }
        ];

        const praMatrixColumns = [
            { score: 1, label: 'Insignificant' },
            { score: 2, label: 'Minor' },
            { score: 3, label: 'Significant' },
            { score: 4, label: 'Major' },
            { score: 5, label: 'Severe' }
        ];

        const praMatrixRows = [
            {
                score: 5,
                label: 'Almost certain',
                cells: [
                    { tone: 'Medium', score: 5 },
                    { tone: 'High', score: 10 },
                    { tone: 'Very high', score: 15 },
                    { tone: 'Extreme', score: 20 },
                    { tone: 'Extreme', score: 25 }
                ]
            },
            {
                score: 4,
                label: 'Likely',
                cells: [
                    { tone: 'Medium', score: 4 },
                    { tone: 'Medium', score: 8 },
                    { tone: 'High', score: 12 },
                    { tone: 'Very high', score: 16 },
                    { tone: 'Extreme', score: 20 }
                ]
            },
            {
                score: 3,
                label: 'Moderate',
                cells: [
                    { tone: 'Low', score: 3 },
                    { tone: 'Medium', score: 6 },
                    { tone: 'Medium', score: 9 },
                    { tone: 'High', score: 12 },
                    { tone: 'Very high', score: 15 }
                ]
            },
            {
                score: 2,
                label: 'Unlikely',
                cells: [
                    { tone: 'Very low', score: 2 },
                    { tone: 'Low', score: 4 },
                    { tone: 'Medium', score: 6 },
                    { tone: 'Medium', score: 8 },
                    { tone: 'High', score: 10 }
                ]
            },
            {
                score: 1,
                label: 'Rare',
                cells: [
                    { tone: 'Very low', score: 1 },
                    { tone: 'Very low', score: 2 },
                    { tone: 'Low', score: 3 },
                    { tone: 'Medium', score: 4 },
                    { tone: 'Medium', score: 5 }
                ]
            }
        ];

        const testTechniqueOptions = [
            { slug: 'decision-table-testing', label: 'Decision table testing' },
            { slug: 'equivalence-partitioning', label: 'Equivalence partitioning' },
            { slug: 'boundary-value-analysis', label: 'Boundary value analysis' },
            { slug: 'state-transition-testing', label: 'State transition testing' },
            { slug: 'use-case-testing', label: 'Use case testing' },
            { slug: 'pairwise-combinatorial-testing', label: 'Pairwise / combinatorial testing' },
            { slug: 'error-guessing', label: 'Error guessing' },
            { slug: 'exploratory-testing', label: 'Exploratory testing' },
            { slug: 'checklist-based-testing', label: 'Checklist-based testing' },
            { slug: 'classification-tree-technique', label: 'Classification tree technique' }
        ];

        const techniqueIntentOptions = [
            { slug: 'okay', label: 'Okay', tone: 'green', decisionGroup: 'status' },
            { slug: 'proof-not-work', label: "Proof that it doesn't work (find bug)", tone: 'blue', decisionGroup: 'proof' },
            { slug: 'find-out-work', label: "Find out why it works (you didn't expect it to)", tone: 'slate', decisionGroup: 'discovery' },
            { slug: 'proof-work', label: 'Proof that it works (validation and verification)', tone: 'blue', decisionGroup: 'proof' },
            { slug: 'find-out-why-not-work', label: "Find out why it doesn't work (discover root cause)", tone: 'slate', decisionGroup: 'discovery' }
        ];

        const requirementReasonTestingOptions = [
            { id: 'must-internal', label: '1 something that must be done (internal influences f.e. CEO)' },
            { id: 'must-external', label: '2 something that must be done (external influences f.e. government)' },
            { id: 'can-internal', label: '3 something that can be done (internal influences f.e. Business Analist)' },
            { id: 'can-external', label: '4 something that can be done (external influences f.e. share holders)' },
            { id: 'bug-internal', label: '5 bug, internal influences (tester reported)' },
            { id: 'bug-external', label: '6 bug, external influences (customer reported)' }
        ];

        const codeToolOptions = [
            {
                slug: 'selenium',
                label: 'Selenium',
                focus: 'Browser automation',
                languages: 'Java, C#, Python, JavaScript, Ruby, Kotlin'
            },
            {
                slug: 'cypress',
                label: 'Cypress',
                focus: 'Browser automation',
                languages: 'JavaScript, TypeScript'
            },
            {
                slug: 'playwright',
                label: 'Playwright',
                focus: 'Browser automation',
                languages: 'JavaScript/TypeScript, Python, Java, C#'
            },
            {
                slug: 'appium',
                label: 'Appium',
                focus: 'Mobile automation',
                languages: 'Java, JavaScript, Python, Ruby, C#'
            },
            {
                slug: 'robot-framework',
                label: 'Robot Framework',
                focus: 'Keyword-driven automation',
                languages: 'Python, Java (through libraries)'
            },
            {
                slug: 'webdriverio',
                label: 'WebdriverIO',
                focus: 'Browser and mobile automation',
                languages: 'JavaScript, TypeScript'
            }
        ];

        const aiTypesGroups = [
            {
                label: 'Traditional AI',
                toneClass: 'is-traditional',
                items: [
                    {
                        number: 1,
                        glyph: 'PA',
                        title: 'Predictive Analytics',
                        copy: 'Forecast customer behavior, demand patterns, and business risks using historical data.'
                    },
                    {
                        number: 2,
                        glyph: 'CS',
                        title: 'Classification Systems',
                        copy: 'Automatically sort, label, and route data from emails to transactions to support tickets.'
                    },
                    {
                        number: 3,
                        glyph: 'AD',
                        title: 'Anomaly Detection',
                        copy: 'Spot unusual patterns that signal fraud, system failures, or security breaches.'
                    }
                ]
            },
            {
                label: 'Generative AI',
                toneClass: 'is-generative',
                items: [
                    {
                        number: 4,
                        glyph: 'CG',
                        title: 'Content Generation',
                        copy: 'Create drafts, code, and designs from prompts (emails, reports, docs, images).'
                    },
                    {
                        number: 5,
                        glyph: 'WA',
                        title: 'Workflow Automation',
                        copy: 'Plug AI into daily tasks like email sorting, meeting notes, and data cleaning.'
                    },
                    {
                        number: 6,
                        glyph: 'RAG',
                        title: 'Knowledge Systems (RAG)',
                        copy: 'Feed custom data into AI so it answers questions about your business.'
                    }
                ]
            },
            {
                label: 'Agentic AI',
                toneClass: 'is-agentic',
                items: [
                    {
                        number: 7,
                        glyph: 'AT',
                        title: 'AI Agents & Tool Use',
                        copy: 'Create agents that execute tasks using APIs, tools, and external systems.'
                    },
                    {
                        number: 8,
                        glyph: 'MO',
                        title: 'Multi-Agent Orchestration',
                        copy: 'Agents that work together, delegate tasks, and coordinate solutions.'
                    },
                    {
                        number: 9,
                        glyph: 'PI',
                        title: 'AI Product Integration',
                        copy: 'Integrate AI into your products and services.'
                    }
                ]
            }
        ];

        const dtapColumns = [
            {
                version: 'Version 1.8',
                environment: 'Development environment',
                count: 4,
                themeKey: 'development'
            },
            {
                version: 'Version 1.7',
                environment: 'Test environment',
                count: 3,
                themeKey: 'test'
            },
            {
                version: 'Version 1.6',
                environment: 'Acceptance',
                count: 2,
                themeKey: 'acceptance'
            },
            {
                version: 'Version 1.5',
                environment: 'Production',
                count: 1,
                themeKey: 'production'
            }
        ];

        const dtapStageCount = 5;

        const diveDeeperLinksByView = {
            root: [
                {
                    label: 'Dive deeper link 1',
                    url: 'https://testenvansoftware.nl/index-oud.php'
                }
            ],
            'requirement-origin-info': [
                {
                    label: 'Dive deeper link 1',
                    url: 'https://softwaretestingbreak.com/reveal-test-planning-secrets.html'
                }
            ],
            'requirement-language-clarity': [
                {
                    label: 'Dive deeper link 1',
                    url: 'https://softwaretestingbreak.com/reveal-test-planning-secrets-part3.html'
                }
            ],
            'test-methods': [
                {
                    label: 'Dive deeper link 1',
                    url: 'https://softwaretestingbreak.com/testtechnieken.html'
                },
                {
                    label: 'Dive deeper link 2',
                    url: 'https://softwaretestingbreak.com/pra.html'
                }
            ],
            dtap: [
                {
                    label: 'Dive deeper link 1',
                    url: 'https://softwaretestingbreak.com/tobetested.html'
                }
            ]
        };

        function makeSafeCustomLabel(value) {
            return String(value || '')
                .replace(/\s+/g, ' ')
                .trim()
                .slice(0, 80);
        }

        function normalizeCustomOverviewState(raw) {
            const state = {
                views: {},
                links: {}
            };

            if (!raw || typeof raw !== 'object') {
                return state;
            }

            const rawViews = raw.views && typeof raw.views === 'object' ? raw.views : {};
            Object.entries(rawViews).forEach(([viewKey, viewValue]) => {
                if (typeof viewKey !== 'string' || !viewValue || typeof viewValue !== 'object') {
                    return;
                }
                const title = makeSafeCustomLabel(viewValue.title || 'Custom page');
                if (!title) {
                    return;
                }
                state.views[viewKey] = {
                    title,
                    createdAt: typeof viewValue.createdAt === 'string' ? viewValue.createdAt : new Date().toISOString()
                };
            });

            const rawLinks = raw.links && typeof raw.links === 'object' ? raw.links : {};
            Object.entries(rawLinks).forEach(([pageKey, pageLinks]) => {
                if (typeof pageKey !== 'string' || !Array.isArray(pageLinks)) {
                    return;
                }
                const normalizedLinks = pageLinks
                    .map((entry) => {
                        if (!entry || typeof entry !== 'object') {
                            return null;
                        }
                        const label = makeSafeCustomLabel(entry.label);
                        const target = typeof entry.target === 'string' ? entry.target : '';
                        if (!label || !target) {
                            return null;
                        }
                        return {
                            id: typeof entry.id === 'string' ? entry.id : `custom-link-${Date.now().toString(36)}`,
                            label,
                            target,
                            createdAt: typeof entry.createdAt === 'string' ? entry.createdAt : new Date().toISOString()
                        };
                    })
                    .filter((entry) => entry && state.views[entry.target]);

                if (normalizedLinks.length) {
                    state.links[pageKey] = normalizedLinks;
                }
            });

            return state;
        }

        function loadEditModeState() {
            try {
                return window.localStorage.getItem(EDIT_MODE_STORAGE_KEY) === '1';
            } catch (error) {
                return false;
            }
        }

        function saveEditModeState() {
            try {
                window.localStorage.setItem(EDIT_MODE_STORAGE_KEY, isEditMode ? '1' : '0');
            } catch (error) {
                // Ignore storage failures to keep the page usable.
            }
        }

        function loadCustomOverviewState() {
            try {
                const rawValue = window.localStorage.getItem(CUSTOM_OVERVIEW_STORAGE_KEY);
                return normalizeCustomOverviewState(rawValue ? JSON.parse(rawValue) : null);
            } catch (error) {
                return normalizeCustomOverviewState(null);
            }
        }

        function loadDtapThemeState() {
            try {
                const storedValue = window.localStorage.getItem(DTAP_THEME_STORAGE_KEY) || '';
                return dtapThemes[storedValue] ? storedValue : '';
            } catch (error) {
                return '';
            }
        }

        function loadDtapStageState() {
            try {
                const parsed = Number(window.localStorage.getItem(DTAP_STAGE_STORAGE_KEY));
                if (Number.isFinite(parsed)) {
                    return Math.min(Math.max(Math.round(parsed), 0), dtapStageCount - 1);
                }
                return 0;
            } catch (error) {
                return 0;
            }
        }

        function loadNarrationAutoplayState() {
            try {
                return window.localStorage.getItem(NARRATION_AUTOPLAY_STORAGE_KEY) === '1';
            } catch (error) {
                return false;
            }
        }

        function saveCustomOverviewState() {
            try {
                window.localStorage.setItem(CUSTOM_OVERVIEW_STORAGE_KEY, JSON.stringify(customOverviewState));
            } catch (error) {
                // Ignore storage failures to keep the page usable.
            }
        }

        function saveDtapThemeState() {
            try {
                if (currentDtapThemeKey && dtapThemes[currentDtapThemeKey]) {
                    window.localStorage.setItem(DTAP_THEME_STORAGE_KEY, currentDtapThemeKey);
                    return;
                }
                window.localStorage.removeItem(DTAP_THEME_STORAGE_KEY);
            } catch (error) {
                // Ignore storage failures to keep the page usable.
            }
        }

        function saveDtapStageState() {
            try {
                window.localStorage.setItem(DTAP_STAGE_STORAGE_KEY, String(currentDtapStage));
            } catch (error) {
                // Ignore storage failures to keep the page usable.
            }
        }

        function saveNarrationAutoplayState() {
            try {
                window.localStorage.setItem(
                    NARRATION_AUTOPLAY_STORAGE_KEY,
                    isNarrationAutoplayEnabled ? '1' : '0'
                );
            } catch (error) {
                // Ignore storage failures to keep the page usable.
            }
        }

        function createCustomViewKey() {
            return `custom-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 8)}`;
        }

        function isCustomViewKey(viewKey) {
            return Boolean(customOverviewState.views[viewKey]) && !overviewTree[viewKey];
        }

        function getCustomButtonsForView(viewKey) {
            return Array.isArray(customOverviewState.links[viewKey]) ? customOverviewState.links[viewKey] : [];
        }

        function createCustomButtonForView(viewKey, rawLabel) {
            const label = makeSafeCustomLabel(rawLabel);
            if (!label) {
                return null;
            }

            const target = createCustomViewKey();
            const nowIso = new Date().toISOString();
            customOverviewState.views[target] = {
                title: label,
                createdAt: nowIso
            };

            if (!Array.isArray(customOverviewState.links[viewKey])) {
                customOverviewState.links[viewKey] = [];
            }

            customOverviewState.links[viewKey].push({
                id: `custom-link-${Date.now().toString(36)}-${Math.random().toString(36).slice(2, 7)}`,
                label,
                target,
                createdAt: nowIso
            });

            saveCustomOverviewState();
            return target;
        }

        let customOverviewState = loadCustomOverviewState();
        let isEditMode = loadEditModeState();
        let currentDtapThemeKey = loadDtapThemeState();
        let currentDtapStage = loadDtapStageState();
        let isNarrationAutoplayEnabled = loadNarrationAutoplayState();

        function applyPageTheme(themeKey = '') {
            const theme = dtapThemes[themeKey] || defaultPageTheme;
            document.documentElement.style.setProperty('--bg-top', theme.bgTop || defaultPageTheme.bgTop);
            document.documentElement.style.setProperty('--bg-mid', theme.bgMid || defaultPageTheme.bgMid);
            document.documentElement.style.setProperty('--bg-bottom', theme.bgBottom || defaultPageTheme.bgBottom);
        }

        function setDtapTheme(themeKey = '') {
            currentDtapThemeKey = dtapThemes[themeKey] ? themeKey : '';
            saveDtapThemeState();
            applyPageTheme(currentDtapThemeKey);
        }

        function renderDtapStageContent(target) {
            if (currentDtapStage <= 0) {
                return;
            }

            if (currentDtapStage >= 1) {
                const panel = document.createElement('section');
                panel.className = 'dtap-stage-panel';
                panel.innerHTML = `
                    <p class="dtap-stage-copy">Many times in software testing there's so much more to it then first thought...</p>
                `;

                const row = document.createElement('div');
                row.className = 'dtap-expansion-grid';
                row.innerHTML = `
                    <div class="dtap-expansion-spacer" aria-hidden="true"></div>
                    <article class="dtap-expansion-card">
                        <h3 class="dtap-expansion-title">Test environment</h3>
                        <p class="dtap-expansion-copy">Database with only test scenario's data</p>
                    </article>
                    <div class="dtap-expansion-spacer" aria-hidden="true"></div>
                    <article class="dtap-expansion-card">
                        <h3 class="dtap-expansion-title">Production</h3>
                        <p class="dtap-expansion-copy">Database with everything</p>
                    </article>
                `;
                panel.appendChild(row);
                target.appendChild(panel);
            }

            if (currentDtapStage >= 2) {
                const panel = document.createElement('section');
                panel.className = 'dtap-stage-panel';
                panel.innerHTML = `
                    <p class="dtap-stage-copy"><span class="dtap-stage-emphasis">Authentication</span></p>
                    <p class="dtap-stage-copy"><span class="dtap-stage-emphasis">Authorization</span></p>
                `;
                target.appendChild(panel);
            }

            if (currentDtapStage >= 3) {
                const panel = document.createElement('section');
                panel.className = 'dtap-stage-panel';
                panel.innerHTML = `
                    <p class="dtap-stage-copy">Internal an/or third party connections (api's)</p>
                `;
                target.appendChild(panel);
            }

            if (currentDtapStage >= 4) {
                const panel = document.createElement('section');
                panel.className = 'dtap-stage-panel';
                panel.innerHTML = `
                    <p class="dtap-stage-copy">You see where this is going right?... <span class="dtap-stage-emphasis">"testing in the multi-verse"</span></p>
                    <p class="dtap-stage-copy">(so many things to think of)</p>
                `;
                target.appendChild(panel);
            }
        }

        function buildRequirementMatrixViews() {
            return requirementCategories.reduce((views, category) => {
                views[`requirement-${category.slug}`] = {
                    title: category.label,
                    breadcrumb: 'Use the PRA risk matrix below. Every cell continues to the Checking / Testing split.',
                    speechText: category.slug === 'functional'
                        ? "Not every requirement is evenly important, the requirement in the categorie that you just chose, how shall we rate that one? click a number to continue this flow."
                        : '',
                    type: 'matrix',
                    requirementLabel: category.label,
                    nextTarget: 'dtap',
                    diveDeeper: [
                        {
                            label: 'Dive deeper link 1',
                            url: 'https://softwaretestingbreak.com/washingprogram.html'
                        }
                    ]
                };
                return views;
            }, {});
        }

        function buildDecisionChoiceItems(targetPrefix) {
            return [
                {
                    label: 'Okay',
                    target: `${targetPrefix}-okay`,
                    tone: 'green',
                    decisionGroup: 'status'
                },
                {
                    label: 'Not okay',
                    target: 'requirement-origin-info',
                    tone: 'red',
                    decisionGroup: 'status'
                },
                ...techniqueIntentOptions
                    .filter((intent) => intent.slug !== 'okay')
                    .map((intent) => ({
                        label: intent.label,
                        target: `${targetPrefix}-${intent.slug}`,
                        tone: intent.tone,
                        fullRow: true,
                        decisionGroup: intent.decisionGroup || 'discovery'
                    }))
            ];
        }

        function buildTechniqueViews() {
            const views = {
                'test-techniques': {
                    title: 'Test techniques',
                    breadcrumb: 'Choose a test technique to continue.',
                    type: 'menu',
                    layout: 'triple',
                    compact: true,
                    items: testTechniqueOptions.map((technique) => ({
                        label: technique.label,
                        target: `technique-${technique.slug}`
                    }))
                }
            };

            testTechniqueOptions.forEach((technique) => {
                const techniqueKey = `technique-${technique.slug}`;
                views[techniqueKey] = {
                    title: technique.label,
                    breadcrumb: 'Tell me what happened or what is needed next...',
                    type: 'menu',
                    layout: 'decision',
                    compact: true,
                    items: buildDecisionChoiceItems(techniqueKey)
                };

                techniqueIntentOptions.forEach((intent) => {
                    views[`${techniqueKey}-${intent.slug}`] = {
                        title: intent.label,
                        breadcrumb: `Loop back into Tacit knowledge or Explicit knowledge for ${technique.label.toLowerCase()}.`,
                        type: 'menu',
                        layout: 'double',
                        compact: true,
                        items: [
                            {
                                label: 'Tacit knowledge',
                                target: 'tacit-knowledge'
                            },
                            {
                                label: 'Explicit knowledge',
                                target: 'explicit-knowledge-from-techniques'
                            }
                        ]
                    };
                });
            });

            return views;
        }

        function buildCodeToolLoopViews() {
            const views = {};

            codeToolOptions.forEach((tool) => {
                const toolKey = `checking-tool-${tool.slug}`;
                views[toolKey] = {
                    title: tool.label,
                    breadcrumb: 'Tell me what happened or what is needed next...',
                    type: 'menu',
                    layout: 'decision',
                    compact: true,
                    items: buildDecisionChoiceItems(toolKey)
                };

                techniqueIntentOptions.forEach((intent) => {
                    views[`${toolKey}-${intent.slug}`] = {
                        title: intent.label,
                        breadcrumb: `Loop back into Tacit knowledge or Explicit knowledge for ${tool.label.toLowerCase()}.`,
                        type: 'menu',
                        layout: 'double',
                        compact: true,
                        items: [
                            {
                                label: 'Tacit knowledge',
                                target: 'tacit-knowledge'
                            },
                            {
                                label: 'Explicit knowledge',
                                target: 'explicit-knowledge'
                            }
                        ]
                    };
                });
            });

            return views;
        }

        const overviewTree = {
            root: {
                title: 'Requirement categories',
                titleButton: {
                    label: 'Requirement',
                    target: 'requirement-origin-info'
                },
                titleSuffix: ' categories',
                breadcrumb: 'Choose one of the 9 requirement categories to continue to the PRA matrix.',
                speechText: 'Welcome to the test overview, this animation shows how software testing can go in general and is an Codex experiment. As you can see there are several categories of requirements from Business rule requirement to Constraint requirement. This list is not official, there can be other category arrangements. Select a requirement to start testing.',
                type: 'menu',
                layout: 'triple',
                compact: true,
                items: requirementCategories.map((category) => ({
                    label: category.label,
                    target: `requirement-${category.slug}`
                }))
            },
            'requirement-origin-info': {
                title: 'Requirement',
                breadcrumb: 'Choose which requirement question you want to open.',
                type: 'menu-with-intro',
                layout: 'single',
                compact: true,
                expandableActionSection: {
                    summary: 'Requirements, reasons for testing.',
                    ariaLabel: 'Show requirement example buttons',
                    groups: [
                        {
                            items: requirementReasonTestingOptions.slice(0, 2).map((item) => ({
                                label: item.label,
                                target: 'requirement-reason-map',
                                tone: 'slate',
                                small: true
                            }))
                        },
                        {
                            items: requirementReasonTestingOptions.slice(2, 4).map((item) => ({
                                label: item.label,
                                target: 'requirement-reason-map',
                                tone: 'slate',
                                small: true
                            }))
                        },
                        {
                            items: requirementReasonTestingOptions.slice(4).map((item) => ({
                                label: item.label,
                                target: 'requirement-reason-map',
                                tone: 'slate',
                                small: true
                            }))
                        }
                    ]
                },
                intro: {
                    title: 'Requirement',
                    copy: 'Choose the requirement question you want to inspect next.'
                },
                items: [
                    {
                        label: 'Where did this requirement come from?',
                        url: 'https://softwaretestingbreak.com/reveal-test-planning-secrets.html',
                        newWindow: true,
                        soundMode: 'victory'
                    },
                    {
                        label: 'Is this requirement perfect or with errors?',
                        target: 'requirement-language-clarity',
                        tone: 'blue'
                    },
                    {
                        label: 'Requirement categories start',
                        target: 'root'
                    }
                ]
            },
            'requirement-reason-map': {
                title: 'Reason for testing',
                breadcrumb: 'Map the test-plan hierarchy, switch between tacit and explicit, then continue to the main page.',
                type: 'requirement-reason-map',
                speechText: 'Leonardo da Vinci already said it: everything connects to everything else. Change a requirement, change a testcase. Change a database, change of data. Change the domain, change the knowledge level, and so on. Now click all Testcase circles and see what happens.',
                nextTarget: 'root'
            },
            'requirement-language-clarity': {
                title: 'Requirement wording',
                breadcrumb: 'Language alone can already shift the meaning of a requirement.',
                type: 'menu-with-intro',
                layout: 'single',
                compact: true,
                intro: {
                    title: 'Requirement wording',
                    html: `
                        <p class="info-copy">Tomorrow there is a concert in the park.</p>
                        <p class="info-copy"><strong>Tomorrow</strong> there is a <strong>concert</strong> in the <strong>park</strong>.</p>
                        <p class="info-copy">Sometimes the essence is not clear because of language. People also sometimes write things differently from what they actually mean. That alone can already change a requirement.</p>
                    `
                },
                items: [
                    {
                        label: 'Requirement categories start',
                        target: 'root'
                    }
                ]
            },
            ...buildRequirementMatrixViews(),
            dtap: {
                title: 'DTAP',
                breadcrumb: 'Choose the environment version and continue to the test overview.',
                speechText: "many times in projects or company's, the IT team works with several development environments. Development, Test, Acceptance and Production. in development are the latest experiments, in test the new features are tested and so on... production is the lessed developed. The Requirement that you just chose, what shall we say on which environment we are going to test it? choose a submit button",
                type: 'dtap'
            },
            ...buildCodeToolLoopViews(),
            'knowledge-root': {
                title: 'Test overview',
                breadcrumb: 'Choose a direction to continue.',
                speechText: 'What shall we do? It is highly recommended to perform thorough functional testing before diving into test automation. Suppose you are testing a requirement stating that an input field may only contain numeric values. You design three test cases: a numeric, one with letters, and one with a symbol. Then, after release, you discover that a value like 1.1 should never have been accepted. The question is: do you want to focus on automating tests, or on actually testing the software? Choose one',
                type: 'menu',
                layout: 'double',
                items: [
                    {
                        label: 'Checking',
                        target: 'checking',
                        copy: 'Move into checking-related knowledge, structure, and later static quality activities.'
                    },
                    {
                        label: 'Software testing',
                        target: 'testing-bridge',
                        copy: 'Open the software testing branch, pause at structured chaos, and continue into tacit knowledge and explicit knowledge.'
                    }
                ]
            },
            checking: {
                title: 'Checking',
                breadcrumb: 'Choose whether you want to continue through A.i. or through test tools.',
                type: 'menu',
                layout: 'double',
                items: [
                    {
                        label: 'A.i.',
                        target: 'checking-ai',
                        copy: 'Move into the A.i. branch for checking-oriented support, guidance, and future automation ideas.'
                    },
                    {
                        label: 'Test tool',
                        target: 'checking-test-tool',
                        copy: 'Open the tool branch and split it into no-code, low-code, and code-based choices.'
                    }
                ]
            },
            'checking-ai': {
                title: 'A.i.',
                breadcrumb: 'Traditional AI, Generative AI, and Agentic AI with the nine matching use cases.',
                type: 'ai-types'
            },
            'checking-test-tool': {
                title: 'Test tool',
                breadcrumb: 'Choose the type of test tool flow you want to inspect.',
                type: 'menu',
                layout: 'triple',
                compact: true,
                items: [
                    {
                        label: 'No-code',
                        target: 'checking-test-tool-no-code'
                    },
                    {
                        label: 'Low-code',
                        target: 'checking-test-tool-low-code'
                    },
                    {
                        label: 'Code',
                        target: 'checking-test-tool-code'
                    }
                ]
            },
            'checking-test-tool-no-code': {
                title: 'No-code',
                breadcrumb: 'A first holding card for no-code test tools.',
                type: 'info',
                frames: [
                    {
                        title: 'No-code test tools',
                        list: [
                            'Examples often include visual record-and-playback tools and workflow-based web testing suites.',
                            'These tools are useful when teams want quick automation without writing much or any source code.',
                            'We can later split this into web, mobile, API, and enterprise no-code tool families.'
                        ]
                    }
                ]
            },
            'checking-test-tool-low-code': {
                title: 'Low-code',
                breadcrumb: 'A first holding card for low-code test tools.',
                type: 'info',
                frames: [
                    {
                        title: 'Low-code test tools',
                        list: [
                            'Low-code tools usually combine visual building blocks with small scripts or reusable actions.',
                            'They fit teams that want faster setup than full code while still keeping some flexibility.',
                            'We can later expand this into examples, strengths, tradeoffs, and when to choose them.'
                        ]
                    }
                ]
            },
            'checking-test-tool-code': {
                title: 'Code',
                breadcrumb: 'A code-based tool overview with common tools and the programming languages they support.',
                type: 'info',
                frames: [
                    {
                        title: 'Code-based test tools',
                        table: {
                            columns: ['Tool', 'Typical focus', 'Languages supported'],
                            rows: codeToolOptions.map((tool) => ({
                                cells: [tool.label, tool.focus, tool.languages],
                                target: `checking-tool-${tool.slug}`,
                                sound: 'victory'
                            }))
                        }
                    }
                ]
            },
            'testing-bridge': {
                title: 'Structured chaos',
                breadcrumb: 'How is your mindset during testing? choose',
                breadcrumbHtml: 'How is your mindset during testing?<br>choose',
                speechText: 'The purpose of this page is to demonstrate that a software tester always benefits from a companion, someone to brainstorm with, someone to discuss ideas and challenges with. The best tests are sometimes created through the synergy between people. Feel free to experiment with the sliders, and once you think you understand the principle, click Next.',
                type: 'structured-chaos',
                nextLabel: 'Next',
                nextTarget: 'testing'
            },
            testing: {
                title: 'Tacit vs Explicit knowledge',
                breadcrumb: 'Choose the knowledge branch you want to explore.',
                type: 'menu',
                layout: 'double',
                expandableKnowledgeSection: {
                    summary: 'Knowledge overlap',
                    items: [
                        {
                            key: 'domain',
                            term: 'Domain Knowledge',
                            ringLines: ['Domain', 'Knowledge'],
                            description: 'knowledge of the business domain or industry (e.g., insurance, healthcare, finance).'
                        },
                        {
                            key: 'professional',
                            term: 'Professional / Technical Knowledge',
                            ringLines: ['Professional /', 'Technical', 'Knowledge'],
                            description: 'knowledge of the profession itself (e.g., software testing, programming, accounting).'
                        },
                        {
                            key: 'organizational',
                            term: 'Organizational / Business Knowledge',
                            ringLines: ['Organizational /', 'Business', 'Knowledge'],
                            description: 'knowledge of the specific organization, its processes, systems, culture, and people.'
                        }
                    ]
                },
                items: [
                    {
                        label: 'Tacit knowledge',
                        target: 'tacit-knowledge',
                        copy: 'Knowledge that is difficult to write down and often grows through experience, observation, and collaboration.'
                    },
                    {
                        label: 'Explicit knowledge',
                        target: 'explicit-knowledge',
                        copy: 'Knowledge that can be captured, structured, reused, and taught through lists, methods, and documented techniques.'
                    }
                ]
            },
            'tacit-knowledge': {
                title: 'Tacit knowledge',
                breadcrumb: 'Choose which tacit knowledge view you want to inspect.',
                type: 'menu',
                layout: 'triple',
                items: [
                    {
                        label: 'Sematic tacit knowledge',
                        target: 'semantic-tacit',
                        copy: 'Meaning, interpretation, and intuitive understanding that people carry without fully writing it down.'
                    },
                    {
                        label: 'Collective tacit knowledge',
                        target: 'collective-tacit',
                        copy: 'Shared team knowledge that exists through habits, cooperation, and how a group naturally works together.'
                    },
                    {
                        label: 'Relational tacit knowledge',
                        target: 'relational-tacit',
                        copy: 'Knowledge that depends on trust, communication, and how people understand each other in practice.'
                    }
                ]
            },
            'semantic-tacit': {
                title: 'Sematic tacit knowledge',
                breadcrumb: 'A first holding card for semantic tacit knowledge.',
                type: 'info',
                frames: [
                    {
                        title: 'Sematic tacit knowledge',
                        copy: 'This can hold examples of intuitive domain understanding, product language, expectation patterns, and tester instinct that is hard to formalize.'
                    }
                ]
            },
            'collective-tacit': {
                title: 'Collective tacit knowledge',
                breadcrumb: 'A first holding card for collective tacit knowledge.',
                type: 'info',
                frames: [
                    {
                        title: 'Collective tacit knowledge',
                        copy: 'This can later hold examples of team routines, hidden agreements, joint heuristics, and how groups anticipate risk together.'
                    }
                ]
            },
            'relational-tacit': {
                title: 'Relational tacit knowledge',
                breadcrumb: 'A first holding card for relational tacit knowledge.',
                type: 'info',
                frames: [
                    {
                        title: 'Relational tacit knowledge',
                        copy: 'This can later hold examples of stakeholder awareness, social calibration, and the tester’s feel for what matters to whom.'
                    }
                ]
            },
            'explicit-knowledge': {
                title: 'Explicit knowledge',
                breadcrumb: 'Choose whether you want techniques or methods.',
                type: 'menu-with-intro',
                layout: 'double',
                intro: {
                    title: 'Explicit knowledge',
                    copy: 'Explicit knowledge can be described, structured, stored, and shared. Here we split it into test techniques and test methods.'
                },
                items: [
                    {
                        label: 'Test techniques',
                        target: 'test-techniques',
                        copy: 'Open a structured list of common techniques such as decision tables, boundary analysis, and state-based ideas.'
                    },
                    {
                        label: 'Test methods',
                        target: 'test-methods',
                        copy: 'Open a structured list of testing methods and perspectives that guide how testing work is approached.'
                    }
                ]
            },
            'explicit-knowledge-from-techniques': {
                title: 'Explicit knowledge',
                breadcrumb: 'From the techniques branch, continue only into test methods.',
                type: 'menu-with-intro',
                layout: 'single',
                compact: true,
                intro: {
                    title: 'Explicit knowledge',
                    copy: 'You already came from test techniques, so this path now only offers test methods.'
                },
                items: [
                    {
                        label: 'Test methods',
                        target: 'test-methods'
                    }
                ]
            },
            'explicit-knowledge-from-methods': {
                title: 'Explicit knowledge',
                breadcrumb: 'From the methods branch, continue only into test techniques.',
                type: 'menu-with-intro',
                layout: 'single',
                compact: true,
                intro: {
                    title: 'Explicit knowledge',
                    copy: 'You already came from test methods, so this path now only offers test techniques.'
                },
                items: [
                    {
                        label: 'Test techniques',
                        target: 'test-techniques'
                    }
                ]
            },
            ...buildTechniqueViews(),
            'test-methods': {
                title: 'Test methods',
                breadcrumb: 'Choose a test method to continue.',
                type: 'menu',
                layout: 'triple',
                compact: true,
                items: [
                    {
                        label: 'Black-box testing',
                        target: 'method-black-box'
                    },
                    {
                        label: 'White-box testing',
                        target: 'method-white-box'
                    },
                    {
                        label: 'Grey-box testing',
                        target: 'method-grey-box'
                    },
                    {
                        label: 'Static testing',
                        target: 'method-static-testing'
                    },
                    {
                        label: 'Dynamic testing',
                        target: 'method-dynamic-testing'
                    },
                    {
                        label: 'Risk-based testing',
                        target: 'method-risk-based-testing'
                    },
                    {
                        label: 'Analytical-based testing',
                        target: 'method-analytical-based-testing'
                    },
                    {
                        label: 'Model-based testing',
                        target: 'method-model-based-testing'
                    },
                    {
                        label: 'Session-based testing',
                        target: 'method-session-based-testing'
                    },
                    {
                        label: 'Experience-based testing',
                        target: 'method-experience-based-testing'
                    }
                ]
            },
            'method-black-box': {
                title: 'Black-box testing',
                breadcrumb: 'This method loops you back into the tacit-versus-explicit knowledge choice.',
                type: 'menu-with-intro',
                layout: 'double',
                intro: {
                    title: 'Black-box testing',
                    copy: 'Inside this loop you can approach black-box testing again through tacit knowledge or explicit knowledge. This creates the recursive idea you described.'
                },
                items: [
                    {
                        label: 'Tacit knowledge',
                        target: 'tacit-knowledge',
                        copy: 'Re-enter black-box thinking through experience, intuition, interpretation, and team feel.'
                    },
                    {
                        label: 'Explicit knowledge',
                        target: 'explicit-knowledge-from-methods',
                        copy: 'Re-enter black-box thinking through documented techniques, methods, and structured knowledge.'
                    }
                ]
            },
            'method-white-box': {
                title: 'White-box testing',
                breadcrumb: 'A holding card for white-box testing.',
                type: 'info',
                frames: [
                    {
                        title: 'White-box testing',
                        copy: 'This branch is ready for later expansion with structure coverage, statement coverage, branch coverage, path reasoning, and code-aware test thinking.'
                    }
                ]
            },
            'method-grey-box': {
                title: 'Grey-box testing',
                breadcrumb: 'A holding card for grey-box testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Grey-box testing',
                        copy: 'This branch is ready for later expansion with hybrid knowledge, partial architecture awareness, and focused probing of internals plus behavior.'
                    }
                ]
            },
            'method-static-testing': {
                title: 'Static testing',
                breadcrumb: 'A holding card for static testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Static testing',
                        copy: 'This branch is ready for later expansion with reviews, walkthroughs, inspections, static analysis, and defect prevention techniques.'
                    }
                ]
            },
            'method-dynamic-testing': {
                title: 'Dynamic testing',
                breadcrumb: 'A holding card for dynamic testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Dynamic testing',
                        copy: 'This branch is ready for later expansion with execution-driven testing, observation, runtime behavior, and test environment strategy.'
                    }
                ]
            },
            'method-risk-based-testing': {
                title: 'Risk-based testing',
                breadcrumb: 'A holding card for risk-based testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Risk-based testing',
                        copy: 'This branch is ready for later expansion with risk assessment, prioritization, business exposure, and critical-path test planning.'
                    }
                ]
            },
            'method-analytical-based-testing': {
                title: 'Analytical-based testing',
                breadcrumb: 'A holding card for analytical-based testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Analytical-based testing',
                        copy: 'This branch is ready for later expansion with data-informed test design, analytical modeling, and structured reasoning over quality risks.'
                    }
                ]
            },
            'method-model-based-testing': {
                title: 'Model-based testing',
                breadcrumb: 'A holding card for model-based testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Model-based testing',
                        copy: 'This branch is ready for later expansion with models, state descriptions, flow graphs, and automated or semi-automated test derivation.'
                    }
                ]
            },
            'method-session-based-testing': {
                title: 'Session-based testing',
                breadcrumb: 'A holding card for session-based testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Session-based testing',
                        copy: 'This branch is ready for later expansion with charters, time-boxes, note-taking, debriefing, and structured exploratory practice.'
                    }
                ]
            },
            'method-experience-based-testing': {
                title: 'Experience-based testing',
                breadcrumb: 'A holding card for experience-based testing.',
                type: 'info',
                frames: [
                    {
                        title: 'Experience-based testing',
                        copy: 'This branch is ready for later expansion with heuristics, intuition, error guessing, and the accumulated judgment of the tester.'
                    }
                ]
            }
        };

        const historyStack = ['root'];
        let lastPraSelection = null;
        let overviewAudioContext = null;

        function getCurrentViewKey() {
            return historyStack[historyStack.length - 1] || 'root';
        }

        function getViewDefinition(viewKey) {
            if (overviewTree[viewKey]) {
                return overviewTree[viewKey];
            }

            if (!isEditMode) {
                return null;
            }

            const customView = customOverviewState.views[viewKey];
            if (!customView) {
                return null;
            }

            return {
                title: customView.title,
                breadcrumb: 'Custom page created in Edit modus. Add more custom buttons to keep building this route.',
                type: 'custom',
                frames: [
                    {
                        title: customView.title,
                        copy: 'This custom page lives only in Edit modus. Use the add-button area below to create the next step in your own flow.'
                    }
                ]
            };
        }

        function sanitizeHistoryForMode() {
            if (isEditMode) {
                return;
            }

            for (let index = historyStack.length - 1; index >= 0; index -= 1) {
                if (!overviewTree[historyStack[index]]) {
                    historyStack.splice(index, 1);
                }
            }

            if (!historyStack.length) {
                historyStack.push('root');
            }

            if (!overviewTree[getCurrentViewKey()]) {
                historyStack.length = 1;
                historyStack[0] = 'root';
            }
        }

        function updateEditModeToggleUi() {
            editModeToggle.classList.toggle('is-active', isEditMode);
            editModeToggle.setAttribute('aria-pressed', isEditMode ? 'true' : 'false');
            editModeToggleLabel.textContent = isEditMode ? 'Edit modus' : 'View modus';
        }

        function updateAutoplayToggleUi() {
            autoplayToggle.classList.toggle('is-active', isNarrationAutoplayEnabled);
            autoplayToggle.setAttribute('aria-pressed', isNarrationAutoplayEnabled ? 'true' : 'false');
            autoplayToggle.setAttribute(
                'aria-label',
                isNarrationAutoplayEnabled ? 'Autoplay narration on' : 'Autoplay narration off'
            );
        }

        function applyNarrationButtonState() {
            const isEnabled = supportsSpeechSynthesis && activeNarrationText.length > 0;
            narrationButton.disabled = !isEnabled;
            narrationButton.classList.toggle('is-playing', isEnabled && narrationState === 'playing');
            narrationButton.classList.toggle('is-paused', isEnabled && narrationState === 'paused');
            narrationButton.setAttribute(
                'aria-pressed',
                isEnabled && narrationState !== 'idle' ? 'true' : 'false'
            );

            if (!isEnabled) {
                narrationButton.setAttribute('aria-label', 'Page narration unavailable');
                return;
            }

            if (narrationState === 'playing') {
                narrationButton.setAttribute('aria-label', 'Pause page narration');
                return;
            }

            if (narrationState === 'paused') {
                narrationButton.setAttribute('aria-label', 'Resume page narration');
                return;
            }

            narrationButton.setAttribute('aria-label', 'Play page narration');
        }

        function stopNarration() {
            continueNarrationPlayback = null;
            narrationState = 'idle';

            if (supportsSpeechSynthesis) {
                narrationPlaybackToken += 1;
                window.speechSynthesis.cancel();
            }

            applyNarrationButtonState();
        }

        function pauseNarration() {
            if (!supportsSpeechSynthesis || narrationState !== 'playing') return;
            narrationState = 'paused';

            if (window.speechSynthesis.speaking && !window.speechSynthesis.paused) {
                window.speechSynthesis.pause();
            }

            applyNarrationButtonState();
        }

        function resumeNarration() {
            if (!supportsSpeechSynthesis || narrationState !== 'paused') return;
            narrationState = 'playing';
            applyNarrationButtonState();

            if (window.speechSynthesis.paused) {
                window.speechSynthesis.resume();
                return;
            }

            if (typeof continueNarrationPlayback === 'function') {
                window.setTimeout(() => {
                    if (narrationState === 'playing' && typeof continueNarrationPlayback === 'function') {
                        continueNarrationPlayback();
                    }
                }, 0);
            }
        }

        function waitForNarrationVoices(timeoutMs = 2200) {
            if (!supportsSpeechSynthesis) {
                return Promise.resolve([]);
            }

            if (typeof window.speechSynthesis.getVoices !== 'function') {
                return Promise.resolve([]);
            }

            const existingVoices = window.speechSynthesis.getVoices();
            if (existingVoices.length) {
                return Promise.resolve(existingVoices);
            }

            if (narrationVoicesPromise) {
                return narrationVoicesPromise;
            }

            narrationVoicesPromise = new Promise((resolve) => {
                const finish = () => {
                    const voices = window.speechSynthesis.getVoices();
                    narrationVoicesPromise = null;
                    resolve(voices);
                };

                const timer = window.setTimeout(finish, timeoutMs);
                if (typeof window.speechSynthesis.addEventListener === 'function') {
                    window.speechSynthesis.addEventListener('voiceschanged', () => {
                        window.clearTimeout(timer);
                        finish();
                    }, { once: true });
                }

                window.speechSynthesis.getVoices();
            });

            return narrationVoicesPromise;
        }

        function getEnglishNarrationVoice(voices = []) {
            if (!supportsSpeechSynthesis) return null;
            if (!voices.length) return null;

            const preferredVoiceNames = [
                'Microsoft Aria Online (Natural)',
                'Microsoft Jenny Online (Natural)',
                'Microsoft Libby Online (Natural)',
                'Aria Online',
                'Jenny Online',
                'Libby Online',
                'Aria',
                'Jenny',
                'Libby',
                'Emma',
                'Ava',
                'Sonia',
                'Michelle',
                'Zira',
                'Hazel',
                'Susan',
                'Google UK English Female',
                'Google US English',
                'Female'
            ];

            const englishVoices = voices.filter((voice) => typeof voice.lang === 'string' && voice.lang.toLowerCase().startsWith('en'));
            if (!englishVoices.length) {
                return null;
            }

            const scoreVoice = (voice) => {
                const name = (voice.name || '').toLowerCase();
                let score = 0;

                preferredVoiceNames.forEach((preferredName, index) => {
                    if (name.includes(preferredName.toLowerCase())) {
                        score += 400 - (index * 10);
                    }
                });

                if (voice.lang === 'en-US') score += 60;
                if (voice.lang === 'en-GB') score += 55;
                if (voice.default) score += 20;
                if (name.includes('natural')) score += 140;
                if (name.includes('online')) score += 100;
                if (name.includes('neural')) score += 120;
                if (name.includes('premium')) score += 60;
                if (name.includes('female')) score += 120;
                if (name.includes('male')) score -= 120;
                if (name.includes('david') || name.includes('guy') || name.includes('mark')) score -= 60;

                return score;
            };

            return englishVoices
                .slice()
                .sort((left, right) => scoreVoice(right) - scoreVoice(left))[0] || null;
        }

        function updateNarrationButton(view) {
            activeNarrationText = typeof view.speechText === 'string' ? view.speechText.trim() : '';
            narrationState = 'idle';
            continueNarrationPlayback = null;
            applyNarrationButtonState();
        }

        function splitNarrationText(text) {
            return String(text || '')
                .split(/(?<=[.!?])\s+/)
                .map((segment) => segment.trim())
                .filter(Boolean);
        }

        async function playNarration(text) {
            if (!supportsSpeechSynthesis || !text) return;

            stopNarration();
            const playbackToken = narrationPlaybackToken + 1;
            narrationPlaybackToken = playbackToken;

            narrationState = 'playing';
            applyNarrationButtonState();

            const voices = await waitForNarrationVoices();
            if (playbackToken !== narrationPlaybackToken) {
                return;
            }

            const voice = getEnglishNarrationVoice(voices);
            const segments = splitNarrationText(text);
            if (!segments.length) {
                continueNarrationPlayback = null;
                narrationState = 'idle';
                applyNarrationButtonState();
                return;
            }

            const isNaturalVoice = Boolean(voice && /natural|online|neural/i.test(voice.name || ''));
            const playbackRate = isNaturalVoice ? 0.98 : 0.9;
            const playbackPitch = isNaturalVoice ? 1.03 : 1.08;

            let segmentIndex = 0;

            const speakNextSegment = () => {
                if (playbackToken !== narrationPlaybackToken) {
                    return;
                }

                continueNarrationPlayback = speakNextSegment;

                if (narrationState === 'paused') {
                    return;
                }

                if (segmentIndex >= segments.length) {
                    continueNarrationPlayback = null;
                    narrationState = 'idle';
                    applyNarrationButtonState();
                    return;
                }

                const utterance = new SpeechSynthesisUtterance(segments[segmentIndex]);
                utterance.lang = voice?.lang || 'en-US';
                utterance.rate = playbackRate;
                utterance.pitch = playbackPitch;
                utterance.volume = 1;

                if (voice) {
                    utterance.voice = voice;
                }

                utterance.onend = () => {
                    if (playbackToken !== narrationPlaybackToken) {
                        return;
                    }
                    segmentIndex += 1;
                    window.setTimeout(() => {
                        if (playbackToken !== narrationPlaybackToken) {
                            return;
                        }
                        if (narrationState === 'paused') {
                            continueNarrationPlayback = speakNextSegment;
                            return;
                        }
                        speakNextSegment();
                    }, 110);
                };

                utterance.onerror = () => {
                    if (playbackToken !== narrationPlaybackToken) {
                        return;
                    }
                    continueNarrationPlayback = null;
                    narrationState = 'idle';
                    applyNarrationButtonState();
                };

                window.speechSynthesis.speak(utterance);
            };

            speakNextSegment();
        }

        function maybeAutoplayNarration(view, viewKey) {
            if (!isNarrationAutoplayEnabled || !supportsSpeechSynthesis || !activeNarrationText || narrationButton.disabled) {
                return;
            }

            const expectedText = typeof view.speechText === 'string' ? view.speechText.trim() : '';
            window.setTimeout(() => {
                if (!isNarrationAutoplayEnabled) {
                    return;
                }
                if (getCurrentViewKey() !== viewKey) {
                    return;
                }
                if (!expectedText || activeNarrationText !== expectedText) {
                    return;
                }
                if (narrationState !== 'idle') {
                    return;
                }
                playNarration(expectedText);
            }, 140);
        }

        function getOverviewAudioContext() {
            try {
                if (!overviewAudioContext) {
                    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContextClass) return null;
                    overviewAudioContext = new AudioContextClass();
                }
                if (overviewAudioContext.state === 'suspended') {
                    overviewAudioContext.resume().catch(() => {});
                }
                return overviewAudioContext;
            } catch (error) {
                return null;
            }
        }

        function hashOverviewViewKey(value) {
            let hash = 2166136261;
            for (let index = 0; index < value.length; index += 1) {
                hash ^= value.charCodeAt(index);
                hash = Math.imul(hash, 16777619);
            }
            return hash >>> 0;
        }

        function playOverviewButtonSound(viewKey, direction = 'forward') {
            const audioContext = getOverviewAudioContext();
            if (!audioContext) return;

            const hash = hashOverviewViewKey(viewKey || 'root');
            const now = audioContext.currentTime + 0.005;
            const duration = direction === 'back' ? 0.24 : 0.32;
            const types = ['triangle', 'sawtooth', 'square', 'sine'];
            const baseFrequency = 150 + (hash % 210);
            const colorFrequency = 520 + ((hash >>> 4) % 780);
            const glowFrequency = 920 + ((hash >>> 9) % 920);
            const startFrequency = direction === 'back'
                ? baseFrequency * (1.52 + ((hash >>> 2) % 12) / 100)
                : baseFrequency * (0.7 + ((hash >>> 2) % 14) / 100);
            const endFrequency = direction === 'back'
                ? baseFrequency * (0.74 + ((hash >>> 6) % 9) / 100)
                : baseFrequency * (1.48 + ((hash >>> 6) % 16) / 100);

            const masterGain = audioContext.createGain();
            masterGain.gain.setValueAtTime(0.0001, now);
            masterGain.gain.exponentialRampToValueAtTime(0.18, now + 0.018);
            masterGain.gain.exponentialRampToValueAtTime(0.0001, now + duration + 0.06);
            masterGain.connect(audioContext.destination);

            const mainOscillator = audioContext.createOscillator();
            const mainGain = audioContext.createGain();
            mainOscillator.type = types[hash % types.length];
            mainOscillator.frequency.setValueAtTime(startFrequency, now);
            mainOscillator.frequency.exponentialRampToValueAtTime(endFrequency, now + duration);
            mainGain.gain.setValueAtTime(0.0001, now);
            mainGain.gain.exponentialRampToValueAtTime(0.19, now + 0.02);
            mainGain.gain.exponentialRampToValueAtTime(0.0001, now + duration);
            mainOscillator.connect(mainGain);
            mainGain.connect(masterGain);
            mainOscillator.start(now);
            mainOscillator.stop(now + duration + 0.03);

            const colorOscillator = audioContext.createOscillator();
            const colorGain = audioContext.createGain();
            colorOscillator.type = types[(hash >>> 3) % types.length];
            colorOscillator.frequency.setValueAtTime(colorFrequency, now + 0.02);
            colorOscillator.frequency.exponentialRampToValueAtTime(
                direction === 'back' ? colorFrequency * 0.82 : colorFrequency * 1.12,
                now + duration * 0.8
            );
            colorGain.gain.setValueAtTime(0.0001, now + 0.01);
            colorGain.gain.exponentialRampToValueAtTime(0.085, now + 0.045);
            colorGain.gain.exponentialRampToValueAtTime(0.0001, now + duration * 0.85);
            colorOscillator.connect(colorGain);
            colorGain.connect(masterGain);
            colorOscillator.start(now + 0.01);
            colorOscillator.stop(now + duration + 0.02);

            const glowOscillator = audioContext.createOscillator();
            const glowGain = audioContext.createGain();
            glowOscillator.type = 'triangle';
            glowOscillator.frequency.setValueAtTime(glowFrequency, now + 0.03);
            glowOscillator.frequency.exponentialRampToValueAtTime(
                direction === 'back' ? glowFrequency * 0.9 : glowFrequency * 1.18,
                now + duration * 0.62
            );
            glowGain.gain.setValueAtTime(0.0001, now + 0.025);
            glowGain.gain.exponentialRampToValueAtTime(0.042, now + 0.055);
            glowGain.gain.exponentialRampToValueAtTime(0.0001, now + duration * 0.58);
            glowOscillator.connect(glowGain);
            glowGain.connect(masterGain);
            glowOscillator.start(now + 0.025);
            glowOscillator.stop(now + duration * 0.64);

            const clickOscillator = audioContext.createOscillator();
            const clickGain = audioContext.createGain();
            clickOscillator.type = 'square';
            clickOscillator.frequency.setValueAtTime(direction === 'back' ? 1380 : 1180, now);
            clickOscillator.frequency.exponentialRampToValueAtTime(direction === 'back' ? 560 : 740, now + 0.06);
            clickGain.gain.setValueAtTime(0.0001, now);
            clickGain.gain.exponentialRampToValueAtTime(0.03, now + 0.008);
            clickGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.07);
            clickOscillator.connect(clickGain);
            clickGain.connect(masterGain);
            clickOscillator.start(now);
            clickOscillator.stop(now + 0.08);
        }

        function playOverviewVictorySound(viewKey) {
            const audioContext = getOverviewAudioContext();
            if (!audioContext) return;

            const hash = hashOverviewViewKey(viewKey || 'victory');
            const now = audioContext.currentTime + 0.005;
            const masterGain = audioContext.createGain();
            masterGain.gain.setValueAtTime(0.0001, now);
            masterGain.gain.exponentialRampToValueAtTime(0.2, now + 0.02);
            masterGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.62);
            masterGain.connect(audioContext.destination);

            [1, 1.26, 1.6].forEach((ratio, index) => {
                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();
                const baseFrequency = 320 + ((hash >>> (index * 3)) % 120);
                oscillator.type = index === 1 ? 'triangle' : 'sine';
                oscillator.frequency.setValueAtTime(baseFrequency * ratio, now + index * 0.055);
                oscillator.frequency.exponentialRampToValueAtTime(baseFrequency * ratio * 1.26, now + 0.22 + index * 0.05);
                gain.gain.setValueAtTime(0.0001, now + index * 0.05);
                gain.gain.exponentialRampToValueAtTime(0.11 - index * 0.02, now + 0.045 + index * 0.05);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.34 + index * 0.07);
                oscillator.connect(gain);
                gain.connect(masterGain);
                oscillator.start(now + index * 0.05);
                oscillator.stop(now + 0.38 + index * 0.08);
            });

            const sparkleOscillator = audioContext.createOscillator();
            const sparkleGain = audioContext.createGain();
            sparkleOscillator.type = 'square';
            sparkleOscillator.frequency.setValueAtTime(1240 + (hash % 200), now + 0.08);
            sparkleOscillator.frequency.exponentialRampToValueAtTime(1880 + (hash % 220), now + 0.18);
            sparkleGain.gain.setValueAtTime(0.0001, now + 0.075);
            sparkleGain.gain.exponentialRampToValueAtTime(0.04, now + 0.1);
            sparkleGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.24);
            sparkleOscillator.connect(sparkleGain);
            sparkleGain.connect(masterGain);
            sparkleOscillator.start(now + 0.075);
            sparkleOscillator.stop(now + 0.25);
        }

        function playRequirementReasonOutcomeSound(outcomeType) {
            const audioContext = getOverviewAudioContext();
            if (!audioContext) return;

            const now = audioContext.currentTime + 0.005;
            const masterGain = audioContext.createGain();
            masterGain.gain.setValueAtTime(0.0001, now);
            masterGain.gain.exponentialRampToValueAtTime(outcomeType === 'nothing' ? 0.16 : 0.14, now + 0.018);
            masterGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.78);
            masterGain.connect(audioContext.destination);

            const notes = outcomeType === 'nothing'
                ? [392, 523.25, 659.25, 783.99]
                : [392, 311.13, 246.94];

            notes.forEach((frequency, index) => {
                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();
                const startAt = now + index * (outcomeType === 'nothing' ? 0.07 : 0.11);
                oscillator.type = outcomeType === 'nothing' ? (index % 2 ? 'triangle' : 'sine') : 'sawtooth';
                oscillator.frequency.setValueAtTime(frequency, startAt);
                oscillator.frequency.exponentialRampToValueAtTime(
                    outcomeType === 'nothing' ? frequency * 1.08 : frequency * 0.78,
                    startAt + 0.18
                );
                gain.gain.setValueAtTime(0.0001, startAt);
                gain.gain.exponentialRampToValueAtTime(outcomeType === 'nothing' ? 0.075 : 0.06, startAt + 0.028);
                gain.gain.exponentialRampToValueAtTime(0.0001, startAt + (outcomeType === 'nothing' ? 0.25 : 0.32));
                oscillator.connect(gain);
                gain.connect(masterGain);
                oscillator.start(startAt);
                oscillator.stop(startAt + 0.36);
            });

            if (outcomeType === 'layer') {
                const thudOscillator = audioContext.createOscillator();
                const thudGain = audioContext.createGain();
                thudOscillator.type = 'triangle';
                thudOscillator.frequency.setValueAtTime(96, now + 0.38);
                thudOscillator.frequency.exponentialRampToValueAtTime(54, now + 0.58);
                thudGain.gain.setValueAtTime(0.0001, now + 0.36);
                thudGain.gain.exponentialRampToValueAtTime(0.075, now + 0.4);
                thudGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.68);
                thudOscillator.connect(thudGain);
                thudGain.connect(masterGain);
                thudOscillator.start(now + 0.36);
                thudOscillator.stop(now + 0.7);
            }
        }

        function playRequirementReasonSlotTick(stepIndex = 0) {
            const audioContext = getOverviewAudioContext();
            if (!audioContext) return;

            const now = audioContext.currentTime + 0.002;
            const masterGain = audioContext.createGain();
            masterGain.gain.setValueAtTime(0.0001, now);
            masterGain.gain.exponentialRampToValueAtTime(0.055, now + 0.006);
            masterGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.07);
            masterGain.connect(audioContext.destination);

            const oscillator = audioContext.createOscillator();
            oscillator.type = stepIndex % 2 ? 'square' : 'triangle';
            oscillator.frequency.setValueAtTime(620 + (stepIndex % 5) * 86, now);
            oscillator.frequency.exponentialRampToValueAtTime(420 + (stepIndex % 3) * 54, now + 0.055);
            oscillator.connect(masterGain);
            oscillator.start(now);
            oscillator.stop(now + 0.075);
        }

        function navigateToView(target, sourceViewKey = getCurrentViewKey(), soundMode = 'forward') {
            if (!getViewDefinition(target)) return;
            if (soundMode === 'victory') {
                playOverviewVictorySound(sourceViewKey);
            } else {
                playOverviewButtonSound(sourceViewKey, 'forward');
            }
            historyStack.push(target);
            renderCurrentView();
        }

        function createMenuButton(item) {
            const button = document.createElement('button');
            button.className = 'choice-button';
            button.type = 'button';
            if (item.compact) {
                button.classList.add('is-compact');
            }
            if (item.tone === 'green') {
                button.classList.add('is-tone-green');
            } else if (item.tone === 'blue') {
                button.classList.add('is-tone-blue');
            } else if (item.tone === 'slate') {
                button.classList.add('is-tone-slate');
            } else if (item.tone === 'red') {
                button.classList.add('is-tone-red');
            }
            if (item.fullRow) {
                button.classList.add('is-span-full');
            }
            if (item.small) {
                button.classList.add('is-small');
            }
            button.innerHTML = `<span class="choice-label">${item.label}</span>`;
            if (item.copy) {
                const copy = document.createElement('span');
                copy.className = 'choice-copy';
                copy.textContent = item.copy;
                button.appendChild(copy);
            }
            button.addEventListener('click', () => {
                const sourceViewKey = getCurrentViewKey();
                const soundMode = item.soundMode || 'forward';
                if (item.url) {
                    if (soundMode === 'victory') {
                        playOverviewVictorySound(sourceViewKey);
                    } else {
                        playOverviewButtonSound(sourceViewKey, 'forward');
                    }
                    window.open(
                        item.url,
                        item.newWindow ? '_blank' : '_self',
                        item.newWindow ? 'noopener,noreferrer' : undefined
                    );
                    return;
                }
                navigateToView(item.target, sourceViewKey, soundMode);
            });
            return button;
        }

        function createInfoFrame(frame) {
            const element = document.createElement('article');
            element.className = 'info-frame';
            const titleMarkup = `<h2 class="info-title">${frame.title}</h2>`;
            if (Array.isArray(frame.list)) {
                const listMarkup = frame.list.map((entry) => `<li>${entry}</li>`).join('');
                element.innerHTML = `${titleMarkup}<ul class="info-list">${listMarkup}</ul>`;
                return element;
            }
            if (typeof frame.html === 'string' && frame.html.trim()) {
                element.innerHTML = `${titleMarkup}${frame.html}`;
                return element;
            }
            if (frame.table && Array.isArray(frame.table.columns) && Array.isArray(frame.table.rows)) {
                element.innerHTML = titleMarkup;
                if (frame.copy) {
                    const copy = document.createElement('p');
                    copy.className = 'info-copy';
                    copy.textContent = frame.copy;
                    element.appendChild(copy);
                }
                const wrap = document.createElement('div');
                wrap.className = 'info-table-wrap';
                const table = document.createElement('table');
                table.className = 'info-table';
                const thead = document.createElement('thead');
                const headRow = document.createElement('tr');
                frame.table.columns.forEach((column) => {
                    const th = document.createElement('th');
                    th.scope = 'col';
                    th.textContent = column;
                    headRow.appendChild(th);
                });
                thead.appendChild(headRow);
                table.appendChild(thead);
                const tbody = document.createElement('tbody');
                frame.table.rows.forEach((row) => {
                    const rowConfig = Array.isArray(row) ? { cells: row } : row;
                    const tr = document.createElement('tr');
                    if (rowConfig.target && getViewDefinition(rowConfig.target)) {
                        tr.classList.add('is-clickable');
                        tr.tabIndex = 0;
                        tr.setAttribute('role', 'button');
                        tr.setAttribute('aria-label', `Open ${rowConfig.cells[0]}`);
                        const activateRow = () => {
                            navigateToView(
                                rowConfig.target,
                                `${getCurrentViewKey()}-${rowConfig.target}-row`,
                                rowConfig.sound === 'victory' ? 'victory' : 'forward'
                            );
                        };
                        tr.addEventListener('click', activateRow);
                        tr.addEventListener('keydown', (event) => {
                            if (event.key !== 'Enter' && event.key !== ' ') return;
                            event.preventDefault();
                            activateRow();
                        });
                    }
                    (rowConfig.cells || []).forEach((cell) => {
                        const td = document.createElement('td');
                        td.textContent = cell;
                        tr.appendChild(td);
                    });
                    tbody.appendChild(tr);
                });
                table.appendChild(tbody);
                wrap.appendChild(table);
                element.appendChild(wrap);
                return element;
            }
            element.innerHTML = `${titleMarkup}<p class="info-copy">${frame.copy || ''}</p>`;
            return element;
        }

        function renderMenu(view) {
            if (view.layout === 'decision') {
                renderDecisionMenu(view);
                return;
            }

            const grid = document.createElement('div');
            const layoutClass = view.layout === 'triple'
                ? 'choice-grid is-triple'
                : view.layout === 'single'
                    ? 'choice-grid is-single'
                    : view.layout === 'decision'
                        ? 'choice-grid is-decision'
                    : 'choice-grid';
            grid.className = layoutClass;
            (view.items || []).forEach((item) => {
                const nextItem = view.compact ? { ...item, compact: true } : item;
                grid.appendChild(createMenuButton(nextItem));
            });
            overviewContent.appendChild(grid);
        }

        function renderDecisionMenu(view) {
            const layout = document.createElement('div');
            layout.className = 'decision-layout';

            const statusRow = document.createElement('div');
            statusRow.className = 'decision-status-row';

            const intentShell = document.createElement('section');
            intentShell.className = 'decision-intent-shell';

            const intentGrid = document.createElement('div');
            intentGrid.className = 'decision-intent-grid';

            const proofColumn = document.createElement('div');
            proofColumn.className = 'decision-intent-column is-proof';

            const discoveryColumn = document.createElement('div');
            discoveryColumn.className = 'decision-intent-column is-discovery';

            (view.items || []).forEach((item) => {
                const button = createMenuButton(view.compact ? { ...item, compact: true } : item);
                if (item.decisionGroup === 'status') {
                    statusRow.appendChild(button);
                    return;
                }
                if (item.decisionGroup === 'proof') {
                    proofColumn.appendChild(button);
                    return;
                }
                discoveryColumn.appendChild(button);
            });

            if (statusRow.childElementCount) {
                layout.appendChild(statusRow);
            }

            if (proofColumn.childElementCount || discoveryColumn.childElementCount) {
                if (proofColumn.childElementCount) {
                    intentGrid.appendChild(proofColumn);
                }
                if (discoveryColumn.childElementCount) {
                    intentGrid.appendChild(discoveryColumn);
                }
                intentShell.appendChild(intentGrid);
                layout.appendChild(intentShell);
            }

            overviewContent.appendChild(layout);
        }

        function renderKnowledgeOverlapSection(config) {
            if (!config || !Array.isArray(config.items) || !config.items.length) {
                return;
            }

            const shell = document.createElement('div');
            shell.className = 'knowledge-overlap-shell';

            const panel = document.createElement('details');
            panel.className = 'knowledge-overlap-panel';

            const summary = document.createElement('summary');
            summary.className = 'knowledge-overlap-summary';
            summary.setAttribute('aria-label', 'Show knowledge overlap details');
            summary.innerHTML = `
                <span class="knowledge-overlap-summary-text">
                    <span class="knowledge-overlap-summary-kicker">Expand</span>
                    <span class="knowledge-overlap-summary-title">${config.summary || 'Knowledge overlap'}</span>
                </span>
                <span class="knowledge-overlap-summary-icon" aria-hidden="true">+</span>
            `;
            panel.appendChild(summary);

            const body = document.createElement('div');
            body.className = 'knowledge-overlap-body';

            const visual = document.createElement('div');
            visual.className = 'knowledge-overlap-visual';

            const diagram = document.createElement('div');
            diagram.className = 'knowledge-overlap-diagram';
            diagram.setAttribute(
                'aria-label',
                'Three overlapping knowledge rings for domain knowledge, professional and technical knowledge, and organizational and business knowledge.'
            );
            diagram.setAttribute('role', 'img');

            config.items.forEach((item) => {
                const ring = document.createElement('div');
                ring.className = `knowledge-overlap-ring is-${item.key || 'generic'}`;

                const label = document.createElement('span');
                label.className = 'knowledge-overlap-ring-label';
                (item.ringLines || [item.term || '']).forEach((line) => {
                    const lineSpan = document.createElement('span');
                    lineSpan.textContent = line;
                    label.appendChild(lineSpan);
                });

                ring.appendChild(label);
                diagram.appendChild(ring);
            });

            visual.appendChild(diagram);
            body.appendChild(visual);

            const copy = document.createElement('div');
            copy.className = 'knowledge-overlap-copy';

            config.items.forEach((item) => {
                const note = document.createElement('p');
                note.className = 'knowledge-overlap-note';

                const term = document.createElement('strong');
                term.className = 'knowledge-overlap-note-term';
                term.textContent = item.term || '';

                note.appendChild(term);
                note.appendChild(document.createTextNode(` - ${item.description || ''}`));
                copy.appendChild(note);
            });

            body.appendChild(copy);
            panel.appendChild(body);
            shell.appendChild(panel);
            overviewContent.appendChild(shell);
        }

        function renderExpandableActionSection(config) {
            if (!config || !Array.isArray(config.groups) || !config.groups.length) {
                return;
            }

            const shell = document.createElement('div');
            shell.className = 'expandable-action-shell';

            const panel = document.createElement('details');
            panel.className = 'expandable-action-panel';

            const summary = document.createElement('summary');
            summary.className = 'expandable-action-summary';
            summary.setAttribute('aria-label', config.ariaLabel || 'Show extra requirement examples');
            summary.innerHTML = `
                <span class="expandable-action-summary-text">
                    <span class="expandable-action-summary-kicker">Expand</span>
                    <span class="expandable-action-summary-title">${config.summary || 'More examples'}</span>
                </span>
                <span class="expandable-action-summary-icon" aria-hidden="true">+</span>
            `;
            panel.appendChild(summary);

            const body = document.createElement('div');
            body.className = 'expandable-action-body';

            config.groups.forEach((group) => {
                const wrap = document.createElement('div');
                wrap.className = 'expandable-action-group';
                (group.items || []).forEach((item) => {
                    wrap.appendChild(createMenuButton(item));
                });
                body.appendChild(wrap);
            });

            panel.appendChild(body);
            shell.appendChild(panel);
            overviewContent.appendChild(shell);
        }

        function renderInfoFrames(frames) {
            (frames || []).forEach((frame) => overviewContent.appendChild(createInfoFrame(frame)));
        }

        function renderRequirementReasonMap(view) {
            const stage = document.createElement('section');
            stage.className = 'reason-map-stage';

            const frame = document.createElement('section');
            frame.className = 'reason-map-frame';

            const plan = document.createElement('div');
            plan.className = 'reason-map-plan';
            plan.textContent = 'Test plan';
            frame.appendChild(plan);

            const scriptWrap = document.createElement('div');
            scriptWrap.className = 'reason-map-script-wrap';

            const scriptNode = document.createElement('div');
            scriptNode.className = 'reason-map-node is-script';
            scriptNode.textContent = 'Test script';
            scriptWrap.appendChild(scriptNode);
            frame.appendChild(scriptWrap);

            const requirementNode = document.createElement('button');
            requirementNode.className = 'reason-map-requirement';
            requirementNode.type = 'button';
            requirementNode.setAttribute('aria-live', 'polite');
            frame.appendChild(requirementNode);

            const requirementLabels = [
                'requirement 1',
                'f.e. [storynmbr-pra.prio-requirementcategory-number]',
                '138363-25-FR-001'
            ];
            let requirementLabelIndex = 0;
            let requirementFlipTimer = null;
            const updateRequirementNode = () => {
                requirementNode.textContent = requirementLabels[requirementLabelIndex];
                requirementNode.classList.toggle('is-code', requirementLabelIndex > 0);
                requirementNode.setAttribute('aria-label', `Requirement identifier: ${requirementLabels[requirementLabelIndex]}`);
            };
            requirementNode.addEventListener('click', () => {
                if (requirementFlipTimer) {
                    window.clearTimeout(requirementFlipTimer);
                }
                requirementLabelIndex = (requirementLabelIndex + 1) % requirementLabels.length;
                requirementNode.classList.remove('is-flipping');
                void requirementNode.offsetWidth;
                requirementNode.classList.add('is-flipping');
                playOverviewButtonSound(`${getCurrentViewKey()}-requirement-flip`, 'forward');
                requirementFlipTimer = window.setTimeout(updateRequirementNode, 210);
                window.setTimeout(() => {
                    requirementNode.classList.remove('is-flipping');
                }, 640);
            });
            updateRequirementNode();

            const branches = document.createElement('div');
            branches.className = 'reason-map-branches';

            const toggleModes = [
                'Tacit knowledge based test',
                'Explicit knowledge based test'
            ];
            const triggerOutcomes = [
                { label: 'Front-end', type: 'layer', weight: 14 },
                { label: 'Back-end', type: 'layer', weight: 14 },
                { label: 'nothing', type: 'nothing', weight: 72 }
            ];
            const triggerRollLabels = triggerOutcomes.map((outcome) => outcome.label);
            const pickTriggerOutcome = () => {
                const totalWeight = triggerOutcomes.reduce((total, outcome) => total + outcome.weight, 0);
                let cursor = Math.random() * totalWeight;
                for (const outcome of triggerOutcomes) {
                    cursor -= outcome.weight;
                    if (cursor <= 0) {
                        return outcome;
                    }
                }
                return triggerOutcomes[triggerOutcomes.length - 1];
            };
            const reasonLabels = requirementReasonTestingOptions.map((item) => item.label);
            const createRouletteQueue = (currentLabel) => {
                const queue = [...reasonLabels];
                for (let queueIndex = queue.length - 1; queueIndex > 0; queueIndex -= 1) {
                    const swapIndex = Math.floor(Math.random() * (queueIndex + 1));
                    [queue[queueIndex], queue[swapIndex]] = [queue[swapIndex], queue[queueIndex]];
                }

                if (queue.length > 1 && queue[0] === currentLabel) {
                    const replacementIndex = queue.findIndex((label) => label !== currentLabel);
                    [queue[0], queue[replacementIndex]] = [queue[replacementIndex], queue[0]];
                }

                return queue;
            };
            const drawRouletteReason = (branchState) => {
                if (!branchState.reasonQueue.length) {
                    branchState.reasonQueue = createRouletteQueue(branchState.reasonLabel);
                }
                branchState.reasonLabel = branchState.reasonQueue.shift() || '';
            };
            const branchStates = [
                { label: 'Test case 1', modeIndex: null, layer: '', triggerType: '', hasSpun: false, isRolling: false, rollToken: 0, rollTimer: null, reasonLabel: '', reasonQueue: [] },
                { label: 'Test case 2', modeIndex: null, layer: '', triggerType: '', hasSpun: false, isRolling: false, rollToken: 0, rollTimer: null, reasonLabel: '', reasonQueue: [] },
                { label: 'Test case 3', modeIndex: null, layer: '', triggerType: '', hasSpun: false, isRolling: false, rollToken: 0, rollTimer: null, reasonLabel: '', reasonQueue: [] },
                { label: 'Test case 4', modeIndex: null, layer: '', triggerType: '', hasSpun: false, isRolling: false, rollToken: 0, rollTimer: null, reasonLabel: '', reasonQueue: [] }
            ];

            const robotPanel = document.createElement('section');
            robotPanel.className = 'reason-map-robot-panel';
            robotPanel.hidden = true;
            robotPanel.innerHTML = `
                <div class="reason-map-robot-head">
                    <div class="reason-map-robot-icon" aria-hidden="true">
                        <span class="reason-map-robot-eye is-left"></span>
                        <span class="reason-map-robot-eye is-right"></span>
                        <span class="reason-map-robot-mouth"></span>
                    </div>
                    <div>
                        <p class="reason-map-robot-kicker">Automation temperament</p>
                        <p class="reason-map-robot-title">Adjust the testing companion</p>
                    </div>
                </div>
                <div class="reason-map-robot-sliders"></div>
            `;

            const robotSliders = robotPanel.querySelector('.reason-map-robot-sliders');
            const robotSliderConfig = [
                { label: 'Humor', min: 60, max: 75, value: 75 },
                { label: 'Honesty', min: 90, max: 95, value: 90 },
                { label: 'Discretion', min: 0, max: 100, value: 84, disabled: true }
            ];

            robotSliderConfig.forEach((config) => {
                const row = document.createElement('label');
                row.className = `reason-map-robot-slider${config.disabled ? ' is-disabled' : ''}`;

                const label = document.createElement('span');
                label.className = 'reason-map-robot-slider-label';
                label.textContent = config.label;

                const input = document.createElement('input');
                input.className = 'reason-map-robot-range';
                input.type = 'range';
                input.min = String(config.min);
                input.max = String(config.max);
                input.value = String(config.value);
                input.disabled = Boolean(config.disabled);

                const value = document.createElement('span');
                value.className = 'reason-map-robot-value';
                const syncValue = () => {
                    value.textContent = `${input.value}%`;
                };
                input.addEventListener('input', syncValue);
                syncValue();

                row.appendChild(label);
                row.appendChild(input);
                row.appendChild(value);
                robotSliders.appendChild(row);
            });

            const updateRobotPanelVisibility = () => {
                robotPanel.hidden = !branchStates.every((state) => state.hasSpun)
                    || branchStates.some((state) => state.isRolling);
            };

            branchStates.forEach((branchState, index) => {
                const branch = document.createElement('div');
                branch.className = 'reason-map-branch';

                const caseNode = document.createElement('button');
                caseNode.className = 'reason-map-node is-case';
                caseNode.type = 'button';

                const caseLabel = document.createElement('span');
                caseNode.appendChild(caseLabel);
                branch.appendChild(caseNode);

                const mindmapNode = document.createElement('div');
                mindmapNode.className = 'reason-map-node is-mindmap';
                mindmapNode.textContent = 'Mindmap';
                branch.appendChild(mindmapNode);

                const branchTail = document.createElement('div');
                branchTail.className = 'reason-map-branch-tail';

                const layerPrompt = document.createElement('p');
                layerPrompt.className = 'reason-map-layer-prompt';
                layerPrompt.textContent = 'the test triggers a ...';
                layerPrompt.hidden = true;

                const layerRoller = document.createElement('div');
                layerRoller.className = 'reason-map-layer-roller';
                layerRoller.setAttribute('aria-live', 'polite');
                layerRoller.hidden = true;

                const layerRollerValue = document.createElement('span');
                layerRollerValue.className = 'reason-map-layer-value';
                layerRollerValue.textContent = 'Front-end';
                layerRoller.appendChild(layerRollerValue);

                const preview = document.createElement('article');
                preview.className = 'reason-map-preview';
                preview.hidden = true;
                preview.innerHTML = `
                    <p class="reason-map-preview-title"></p>
                    <p class="reason-map-preview-mode"></p>
                    <p class="reason-map-preview-reason"></p>
                `;

                const previewTitle = preview.querySelector('.reason-map-preview-title');
                const previewMode = preview.querySelector('.reason-map-preview-mode');
                const previewReason = preview.querySelector('.reason-map-preview-reason');

                const updateBranchUi = () => {
                    const modeLabel = branchState.modeIndex === null
                        ? branchState.label
                        : toggleModes[branchState.modeIndex];
                    const nextModeLabel = branchState.modeIndex === null
                        ? toggleModes[0]
                        : toggleModes[(branchState.modeIndex + 1) % toggleModes.length];

                    caseLabel.textContent = modeLabel;
                    caseNode.classList.toggle('is-selected', branchState.modeIndex !== null);
                    caseNode.classList.toggle('is-explicit', branchState.modeIndex === 1);
                    caseNode.setAttribute(
                        'aria-label',
                        branchState.modeIndex === null
                            ? `${branchState.label}, click to toggle to ${nextModeLabel}`
                            : `${modeLabel}, click to switch to ${nextModeLabel}`
                    );

                    layerPrompt.hidden = branchState.modeIndex === null
                        || (!branchState.isRolling && branchState.triggerType === 'nothing');
                    layerRoller.hidden = branchState.modeIndex === null;
                    layerRoller.classList.toggle('is-rolling', branchState.isRolling);
                    layerRoller.classList.toggle('is-settled', !branchState.isRolling && Boolean(branchState.layer));
                    layerRoller.classList.toggle('is-nothing', !branchState.isRolling && branchState.triggerType === 'nothing');
                    layerRoller.setAttribute(
                        'aria-label',
                        branchState.isRolling
                            ? 'Rolling between Front-end, Back-end, and nothing'
                            : (branchState.layer ? `Result ${branchState.layer}` : 'Layer roller waiting')
                    );

                    const shouldShowPreview = branchState.modeIndex !== null
                        && Boolean(branchState.layer)
                        && !branchState.isRolling
                        && branchState.triggerType === 'layer';
                    preview.hidden = !shouldShowPreview;
                    if (shouldShowPreview) {
                        previewTitle.textContent = branchState.layer;
                        previewMode.textContent = '...new thingy, namely';
                        previewReason.textContent = branchState.reasonLabel;
                    }
                };

                const finishLayerRoll = (token, finalOutcome) => {
                    if (token !== branchState.rollToken) return;
                    branchState.isRolling = false;
                    branchState.layer = finalOutcome.label;
                    branchState.triggerType = finalOutcome.type;
                    branchState.hasSpun = true;
                    branchState.rollTimer = null;
                    layerRollerValue.textContent = finalOutcome.label;
                    updateBranchUi();
                    updateRobotPanelVisibility();
                    playRequirementReasonOutcomeSound(finalOutcome.type);
                };

                const startLayerRoll = () => {
                    branchState.rollToken += 1;
                    if (branchState.rollTimer) {
                        window.clearTimeout(branchState.rollTimer);
                    }

                    const token = branchState.rollToken;
                    const finalOutcome = pickTriggerOutcome();
                    const rollDuration = 4000 + Math.floor(Math.random() * 2001);
                    const startedAt = window.performance.now();
                    let rollIndex = Math.floor(Math.random() * triggerRollLabels.length);

                    branchState.layer = '';
                    branchState.triggerType = '';
                    branchState.isRolling = true;
                    layerRollerValue.textContent = triggerRollLabels[rollIndex];
                    playRequirementReasonSlotTick(rollIndex);
                    updateBranchUi();
                    updateRobotPanelVisibility();

                    const tick = () => {
                        if (token !== branchState.rollToken) return;

                        const elapsed = window.performance.now() - startedAt;
                        if (elapsed >= rollDuration) {
                            finishLayerRoll(token, finalOutcome);
                            return;
                        }

                        rollIndex += 1;
                        layerRollerValue.textContent = triggerRollLabels[rollIndex % triggerRollLabels.length];
                        playRequirementReasonSlotTick(rollIndex);

                        const progress = Math.min(elapsed / rollDuration, 1);
                        const nextDelay = 70 + Math.round(560 * progress * progress);
                        branchState.rollTimer = window.setTimeout(tick, nextDelay);
                    };

                    branchState.rollTimer = window.setTimeout(tick, 70);
                };

                caseNode.addEventListener('click', () => {
                    branchState.modeIndex = branchState.modeIndex === null
                        ? 0
                        : (branchState.modeIndex + 1) % toggleModes.length;
                    drawRouletteReason(branchState);
                    startLayerRoll();
                });

                updateBranchUi();

                branchTail.appendChild(layerPrompt);
                branchTail.appendChild(layerRoller);
                branchTail.appendChild(preview);
                branch.appendChild(branchTail);
                branches.appendChild(branch);
            });

            frame.appendChild(branches);
            frame.appendChild(robotPanel);
            updateRobotPanelVisibility();
            stage.appendChild(frame);

            const actions = document.createElement('div');
            actions.className = 'reason-map-actions';
            actions.appendChild(createMenuButton({
                label: view.nextLabel || 'Next',
                target: view.nextTarget || 'root',
                compact: true
            }));
            stage.appendChild(actions);

            overviewContent.appendChild(stage);
        }

        function renderStructuredChaos(view) {
            const stage = document.createElement('section');
            stage.className = 'structured-chaos-stage';
            stage.innerHTML = `
                <div class="structured-chaos-team" role="group" aria-label="Choose whether one or two testers shape the mindset view.">
                    <button class="structured-chaos-tester is-primary is-active" type="button" data-tester="1" aria-pressed="true" aria-label="Primary tester active">
                        <span class="structured-chaos-tester-icon" aria-hidden="true">
                            <svg viewBox="0 0 36 48" focusable="false">
                                <circle cx="18" cy="8" r="5"></circle>
                                <path d="M18 14 L18 29"></path>
                                <path d="M8 20 L18 18 L28 20"></path>
                                <path d="M18 29 L10 41"></path>
                                <path d="M18 29 L26 41"></path>
                            </svg>
                        </span>
                        <span class="structured-chaos-tester-count">1</span>
                    </button>
                    <button class="structured-chaos-tester is-secondary" type="button" data-tester="2" aria-pressed="false" aria-label="Add a second tester">
                        <span class="structured-chaos-tester-icon" aria-hidden="true">
                            <svg viewBox="0 0 36 48" focusable="false">
                                <circle cx="18" cy="8" r="5"></circle>
                                <path d="M18 14 L18 29"></path>
                                <path d="M8 20 L18 18 L28 20"></path>
                                <path d="M18 29 L10 41"></path>
                                <path d="M18 29 L26 41"></path>
                            </svg>
                        </span>
                        <span class="structured-chaos-tester-count">2</span>
                    </button>
                </div>
                <div class="structured-chaos-figure" role="img" aria-label="Creativity rises across structure and intuitive chaos while one or two tester mindset sliders place vertical guides on the curve.">
                    <svg class="structured-chaos-svg" viewBox="0 0 1200 760" aria-hidden="true" focusable="false">
                        <defs>
                            <marker id="structured-chaos-arrow" markerWidth="12" markerHeight="12" refX="6" refY="6" orient="auto">
                                <path d="M0,12 L6,0 L12,12 Z" fill="rgba(255, 229, 162, 0.48)"></path>
                            </marker>
                        </defs>
                        <line class="structured-chaos-axis" x1="145" y1="620" x2="145" y2="90" marker-end="url(#structured-chaos-arrow)"></line>
                        <line class="structured-chaos-axis" x1="145" y1="606" x2="1080" y2="606"></line>
                        <path class="structured-chaos-curve" d="M145 575 C 235 575, 320 512, 410 338 C 475 214, 548 128, 628 124 C 700 120, 776 190, 840 308 C 918 452, 996 572, 1080 575"></path>
                        <line class="structured-chaos-axis is-dashed is-primary structured-chaos-indicator is-primary" x1="620" y1="96" x2="620" y2="642"></line>
                        <rect class="structured-chaos-indicator-cap is-primary" x="603" y="584" width="34" height="44" rx="10"></rect>
                        <line class="structured-chaos-axis is-dashed is-secondary structured-chaos-indicator is-secondary is-hidden" x1="620" y1="96" x2="620" y2="642"></line>
                        <rect class="structured-chaos-indicator-cap is-secondary is-hidden" x="603" y="584" width="34" height="44" rx="10"></rect>
                    </svg>
                    <div class="structured-chaos-label is-y">Creativity</div>
                    <div class="structured-chaos-label is-left">Structure</div>
                    <div class="structured-chaos-label is-right">Chaos [Intuitive]</div>
                </div>
                <div class="structured-chaos-message" aria-live="polite"></div>
                <div class="structured-chaos-slider-stack">
                    <div class="structured-chaos-fader-shell is-primary">
                        <p class="structured-chaos-fader-label">Tester 1</p>
                        <input class="structured-chaos-slider is-primary" type="range" min="0" max="100" step="1" value="50" aria-label="Tester 1 slider from structure to intuitive chaos">
                    </div>
                    <div class="structured-chaos-fader-shell is-secondary" hidden>
                        <p class="structured-chaos-fader-label">Tester 2</p>
                        <input class="structured-chaos-slider is-secondary" type="range" min="0" max="100" step="1" value="50" aria-label="Tester 2 slider from structure to intuitive chaos">
                    </div>
                </div>
            `;

            const primaryButton = stage.querySelector('.structured-chaos-tester.is-primary');
            const secondaryButton = stage.querySelector('.structured-chaos-tester.is-secondary');
            const primaryIndicator = stage.querySelector('.structured-chaos-indicator.is-primary');
            const primaryIndicatorCap = stage.querySelector('.structured-chaos-indicator-cap.is-primary');
            const secondaryIndicator = stage.querySelector('.structured-chaos-indicator.is-secondary');
            const secondaryIndicatorCap = stage.querySelector('.structured-chaos-indicator-cap.is-secondary');
            const message = stage.querySelector('.structured-chaos-message');
            const primarySlider = stage.querySelector('.structured-chaos-slider.is-primary');
            const secondarySlider = stage.querySelector('.structured-chaos-slider.is-secondary');
            const secondaryShell = stage.querySelector('.structured-chaos-fader-shell.is-secondary');
            const minX = 145;
            const maxX = 1080;
            const capOffset = 17;
            const sliderCopy = {
                left: "You can't find the root cause of the bug? maybe experiment more",
                middle: "You can't handle all those test scenario's on your own.",
                right: "Lack of structure might not cover all the scenario's that could be tested then."
            };
            let secondTesterActive = false;

            const getZoneFromValue = (value) => {
                const numericValue = Number(value) || 0;
                if (numericValue <= 33) {
                    return 'left';
                }
                if (numericValue >= 67) {
                    return 'right';
                }
                return 'middle';
            };

            const syncSingleIndicator = (indicator, indicatorCap, sliderValue) => {
                if (!indicator || !indicatorCap) return;
                const ratio = Number(sliderValue) / 100;
                const currentX = minX + ((maxX - minX) * ratio);
                indicator.setAttribute('x1', String(currentX));
                indicator.setAttribute('x2', String(currentX));
                indicatorCap.setAttribute('x', String(currentX - capOffset));
            };

            const renderMessage = () => {
                if (!message || !primarySlider) return;

                const zones = [getZoneFromValue(primarySlider.value)];
                if (secondTesterActive && secondarySlider) {
                    zones.push(getZoneFromValue(secondarySlider.value));
                }

                const activeMessages = [];
                zones.forEach((zone) => {
                    if (zone === 'middle' && secondTesterActive) {
                        return;
                    }
                    const copy = sliderCopy[zone];
                    if (copy && !activeMessages.includes(copy)) {
                        activeMessages.push(copy);
                    }
                });

                message.innerHTML = activeMessages
                    .map((entry) => `<span class="structured-chaos-message-line">${entry}</span>`)
                    .join('');
            };

            const syncAll = () => {
                if (primarySlider) {
                    syncSingleIndicator(primaryIndicator, primaryIndicatorCap, primarySlider.value);
                }
                if (secondarySlider) {
                    syncSingleIndicator(secondaryIndicator, secondaryIndicatorCap, secondarySlider.value);
                }
                renderMessage();
            };

            const setSecondTesterState = (nextState) => {
                secondTesterActive = nextState;
                if (secondaryButton) {
                    secondaryButton.classList.toggle('is-active', nextState);
                    secondaryButton.setAttribute('aria-pressed', nextState ? 'true' : 'false');
                }
                if (secondaryShell) {
                    secondaryShell.hidden = !nextState;
                }
                if (secondaryIndicator) {
                    secondaryIndicator.classList.toggle('is-hidden', !nextState);
                }
                if (secondaryIndicatorCap) {
                    secondaryIndicatorCap.classList.toggle('is-hidden', !nextState);
                }
                syncAll();
            };

            if (primaryButton) {
                primaryButton.addEventListener('click', () => {
                    if (!secondTesterActive) return;
                    playOverviewButtonSound(`${getCurrentViewKey()}-structured-chaos-solo`, 'back');
                    setSecondTesterState(false);
                });
            }

            if (secondaryButton) {
                secondaryButton.addEventListener('click', () => {
                    if (secondTesterActive) return;
                    playOverviewVictorySound(`${getCurrentViewKey()}-structured-chaos-pair`);
                    setSecondTesterState(true);
                });
            }

            if (primarySlider) {
                primarySlider.addEventListener('input', syncAll);
                primarySlider.addEventListener('change', syncAll);
            }

            if (secondarySlider) {
                secondarySlider.addEventListener('input', syncAll);
                secondarySlider.addEventListener('change', syncAll);
            }

            setSecondTesterState(false);

            overviewContent.appendChild(stage);

            const actions = document.createElement('div');
            actions.className = 'structured-chaos-actions';
            actions.appendChild(createMenuButton({
                label: view.nextLabel || 'Next',
                target: view.nextTarget,
                compact: true
            }));
            overviewContent.appendChild(actions);
        }

        function renderEditModePanel(viewKey, view) {
            if (!isEditMode) {
                return;
            }

            const panel = document.createElement('section');
            panel.className = 'edit-panel';

            const head = document.createElement('div');
            head.className = 'edit-panel-head';
            head.innerHTML = `
                <div>
                    <h2 class="edit-panel-title">Edit modus</h2>
                    <p class="edit-panel-copy">Type a custom button for this page. That button opens a new custom page where you can keep adding more buttons and build your own branch.</p>
                </div>
                <span class="edit-panel-chip">Custom path</span>
            `;
            panel.appendChild(head);

            const builder = document.createElement('div');
            builder.className = 'edit-builder';

            const input = document.createElement('input');
            input.className = 'edit-builder-input';
            input.type = 'text';
            input.maxLength = 80;
            input.placeholder = `Type a custom button for ${view.title || 'this page'}`;
            input.setAttribute('aria-label', `Type a custom button for ${view.title || 'this page'}`);

            const submit = document.createElement('button');
            submit.className = 'edit-builder-submit';
            submit.type = 'button';
            submit.textContent = 'Add button';

            const createButton = () => {
                const label = makeSafeCustomLabel(input.value);
                if (!label) {
                    input.focus();
                    return;
                }
                const createdTarget = createCustomButtonForView(viewKey, label);
                if (!createdTarget) {
                    return;
                }
                playOverviewVictorySound(`${viewKey}-custom-create`);
                input.value = '';
                renderCurrentView();
            };

            submit.addEventListener('click', createButton);
            input.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter') {
                    return;
                }
                event.preventDefault();
                createButton();
            });

            builder.appendChild(input);
            builder.appendChild(submit);
            panel.appendChild(builder);

            const customButtons = getCustomButtonsForView(viewKey);
            const customList = document.createElement('div');
            customList.className = 'edit-custom-list';

            const heading = document.createElement('p');
            heading.className = 'edit-custom-heading';
            heading.textContent = 'Custom buttons on this page';
            customList.appendChild(heading);

            if (!customButtons.length) {
                const empty = document.createElement('p');
                empty.className = 'edit-empty';
                empty.textContent = 'No custom buttons on this page yet. Add one and it becomes a new page you can continue editing.';
                customList.appendChild(empty);
            } else {
                const grid = document.createElement('div');
                grid.className = 'choice-grid is-single';
                customButtons.forEach((buttonItem) => {
                    grid.appendChild(createMenuButton({
                        label: buttonItem.label,
                        target: buttonItem.target,
                        compact: true
                    }));
                });
                customList.appendChild(grid);
            }

            panel.appendChild(customList);
            overviewContent.appendChild(panel);
        }

        function renderMatrix(view) {
            const frame = document.createElement('section');
            frame.className = 'matrix-frame';

            const note = document.createElement('p');
            note.className = 'matrix-note';
            note.textContent = `${view.requirementLabel} uses the same PRA matrix here. Click any row/column combination to continue to the Checking / Testing split.`;
            frame.appendChild(note);

            const mobileHint = document.createElement('p');
            mobileHint.className = 'matrix-mobile-hint';
            mobileHint.textContent = 'Swipe sideways on mobile to inspect the full PRA matrix.';
            frame.appendChild(mobileHint);

            const scroll = document.createElement('div');
            scroll.className = 'matrix-scroll';

            const table = document.createElement('table');
            table.className = 'matrix-table';

            const thead = document.createElement('thead');
            const headRow = document.createElement('tr');

            const corner = document.createElement('th');
            corner.className = 'matrix-corner';
            corner.scope = 'col';
            corner.textContent = 'PRA matrix';
            headRow.appendChild(corner);

            praMatrixColumns.forEach((column) => {
                const th = document.createElement('th');
                th.className = 'matrix-axis';
                th.scope = 'col';
                th.innerHTML = `
                    <span class="matrix-axis-title">${column.label}</span>
                    <span class="matrix-axis-subtitle">${column.score}</span>
                `;
                headRow.appendChild(th);
            });

            thead.appendChild(headRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            praMatrixRows.forEach((row) => {
                const tr = document.createElement('tr');

                const rowHead = document.createElement('th');
                rowHead.className = 'matrix-row-label';
                rowHead.scope = 'row';
                rowHead.innerHTML = `
                    <span class="matrix-row-score">${row.score}</span>
                    <span class="matrix-row-text">${row.label}</span>
                `;
                tr.appendChild(rowHead);

                row.cells.forEach((cell, cellIndex) => {
                    const td = document.createElement('td');
                    const column = praMatrixColumns[cellIndex];
                    const button = document.createElement('button');
                    button.className = 'matrix-cell-button';
                    button.type = 'button';
                    button.setAttribute(
                        'aria-label',
                        `${view.requirementLabel}, ${row.label}, ${column.label}, ${cell.tone}, ${cell.score}`
                    );
                    button.innerHTML = `
                        <span class="matrix-cell-tone">${cell.tone}</span>
                        <span class="matrix-cell-score">${cell.score}</span>
                    `;
                    button.addEventListener('click', () => {
                        lastPraSelection = {
                            requirementLabel: view.requirementLabel,
                            tone: cell.tone,
                            score: cell.score
                        };
                        navigateToView(view.nextTarget, `${getCurrentViewKey()}-matrix`);
                    });
                    td.appendChild(button);
                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            scroll.appendChild(table);
            frame.appendChild(scroll);
            overviewContent.appendChild(frame);
        }

        function renderAiTypes() {
            const stage = document.createElement('section');
            stage.className = 'ai-types-stage';

            const head = document.createElement('header');
            head.className = 'ai-types-head';
            head.innerHTML = `
                <p class="ai-types-kicker">A.i. landscape</p>
                <h2 class="ai-types-title">AI TYPES</h2>
            `;
            stage.appendChild(head);

            const stack = document.createElement('div');
            stack.className = 'ai-types-stack';

            aiTypesGroups.forEach((group) => {
                const cluster = document.createElement('section');
                cluster.className = `ai-type-cluster ${group.toneClass}`;

                const core = document.createElement('div');
                core.className = `ai-type-core ${group.toneClass}`;

                const splitLabel = group.label.replace(' AI', '');
                core.innerHTML = `
                    <div>
                        <span class="ai-type-core-small">${splitLabel}</span>
                        <span class="ai-type-core-big">AI</span>
                    </div>
                `;
                cluster.appendChild(core);

                const items = document.createElement('div');
                items.className = 'ai-type-items';

                group.items.forEach((item) => {
                    const entry = document.createElement('article');
                    entry.className = 'ai-type-item';
                    entry.innerHTML = `
                        <div class="ai-type-icon" aria-hidden="true">${item.glyph}</div>
                        <div class="ai-type-item-body">
                            <div class="ai-type-item-head">
                                <span class="ai-type-number">${item.number}</span>
                                <h3 class="ai-type-item-title">${item.title}</h3>
                            </div>
                            <p class="ai-type-item-copy">${item.copy}</p>
                        </div>
                    `;
                    items.appendChild(entry);
                });

                cluster.appendChild(items);
                stack.appendChild(cluster);
            });

            stage.appendChild(stack);
            overviewContent.appendChild(stage);
        }

        function renderDtap() {
            const stack = document.createElement('section');
            stack.className = 'dtap-stack';

            const grid = document.createElement('section');
            grid.className = 'dtap-grid';

            dtapColumns.forEach((column, columnIndex) => {
                const card = document.createElement('article');
                card.className = 'dtap-column';

                const head = document.createElement('div');
                head.className = 'dtap-head';

                const version = document.createElement('h2');
                version.className = 'dtap-version';
                version.textContent = column.version;
                head.appendChild(version);

                const environment = document.createElement('p');
                environment.className = 'dtap-environment';
                environment.textContent = column.environment;
                head.appendChild(environment);

                card.appendChild(head);

                const form = document.createElement('div');
                form.className = 'dtap-form';

                for (let index = 0; index < column.count; index += 1) {
                    const input = document.createElement('input');
                    input.className = 'dtap-input';
                    input.type = 'text';
                    input.setAttribute('aria-label', `${column.environment} input ${index + 1}`);
                    input.value = '...';
                    input.dataset.seedValue = '...';
                    input.classList.add('is-seeded');
                    input.addEventListener('focus', () => {
                        if (input.value === input.dataset.seedValue) {
                            input.value = '';
                            input.classList.remove('is-seeded');
                        }
                    });
                    input.addEventListener('blur', () => {
                        if (input.value.trim()) {
                            input.classList.remove('is-seeded');
                            return;
                        }
                        input.value = input.dataset.seedValue || '...';
                        input.classList.add('is-seeded');
                    });
                    form.appendChild(input);
                }

                for (let spacerIndex = column.count; spacerIndex < 4; spacerIndex += 1) {
                    const spacer = document.createElement('div');
                    spacer.className = 'dtap-input-spacer';
                    spacer.setAttribute('aria-hidden', 'true');
                    form.appendChild(spacer);
                }

                const submit = document.createElement('button');
                submit.className = 'dtap-submit';
                submit.type = 'button';
                submit.textContent = 'Submit';
                const submitTheme = dtapThemes[column.themeKey] || null;
                if (submitTheme) {
                    submit.style.setProperty('--dtap-submit-top', submitTheme.submitTop);
                    submit.style.setProperty('--dtap-submit-bottom', submitTheme.submitBottom);
                    submit.style.setProperty('--dtap-submit-border', submitTheme.submitBorder);
                    submit.style.setProperty('--dtap-submit-border-hover', submitTheme.submitBorderHover);
                    submit.style.setProperty('--dtap-submit-edge', submitTheme.submitEdge);
                    submit.style.setProperty('--dtap-submit-glow', submitTheme.submitGlow);
                }
                submit.addEventListener('click', () => {
                    setDtapTheme(column.themeKey);
                    navigateToView('knowledge-root', `dtap-${columnIndex}`, 'victory');
                });
                form.appendChild(submit);

                card.appendChild(form);
                grid.appendChild(card);
            });

            stack.appendChild(grid);

            const topbar = document.createElement('div');
            topbar.className = 'dtap-topbar';

            const stageShell = document.createElement('div');
            stageShell.className = 'dtap-stage-shell';
            stageShell.innerHTML = `
                <p class="dtap-stage-label">Test environment aspects</p>
                <input class="dtap-stage-range" type="range" min="0" max="4" step="1" value="${currentDtapStage}" aria-label="Test environment aspects slider">
                <div class="dtap-stage-ticks" aria-hidden="true">
                    <span>0</span>
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                </div>
            `;

            const range = stageShell.querySelector('.dtap-stage-range');
            if (range) {
                range.addEventListener('input', () => {
                    currentDtapStage = Number(range.value) || 0;
                    saveDtapStageState();
                    renderCurrentView();
                });
            }

            topbar.appendChild(stageShell);
            stack.appendChild(topbar);
            renderDtapStageContent(stack);
            overviewContent.appendChild(stack);
        }

        function getDiveDeeperLinks(viewKey, view) {
            if (Array.isArray(view.diveDeeper)) {
                return view.diveDeeper;
            }
            return Array.isArray(diveDeeperLinksByView[viewKey]) ? diveDeeperLinksByView[viewKey] : [];
        }

        function renderDiveDeeperPanel(viewKey, view) {
            const shell = document.createElement('aside');
            shell.className = 'dive-deeper-shell';

            const panel = document.createElement('details');
            panel.className = 'dive-deeper-panel';

            const summary = document.createElement('summary');
            summary.className = 'dive-deeper-summary';
            summary.setAttribute('aria-label', 'Dive deeper');
            summary.innerHTML = `
                <span class="dive-deeper-summary-text" aria-hidden="true">Dive deeper</span>
                <span class="dive-deeper-summary-icon" aria-hidden="true">↘</span>
            `;
            panel.appendChild(summary);

            const body = document.createElement('div');
            body.className = 'dive-deeper-body';

            const links = getDiveDeeperLinks(viewKey, view);
            if (!links.length) {
                const empty = document.createElement('p');
                empty.className = 'dive-deeper-empty';
                empty.textContent = 'Links for this page will appear here.';
                body.appendChild(empty);
            } else {
                const list = document.createElement('div');
                list.className = 'dive-deeper-list';
                links.forEach((linkItem, index) => {
                    const anchor = document.createElement('a');
                    anchor.className = 'dive-deeper-link';
                    anchor.href = linkItem.url;
                    anchor.target = '_blank';
                    anchor.rel = 'noopener noreferrer';
                    anchor.innerHTML = `
                        <span class="dive-deeper-link-index">${index + 1}</span>
                        <span class="dive-deeper-link-text">${linkItem.label}</span>
                    `;
                    list.appendChild(anchor);
                });
                body.appendChild(list);
            }

            panel.appendChild(body);
            shell.appendChild(panel);
            overviewContent.appendChild(shell);
        }

        function renderViewAddOns(viewKey, view) {
            renderEditModePanel(viewKey, view);
            renderDiveDeeperPanel(viewKey, view);
        }

        function renderCurrentView() {
            sanitizeHistoryForMode();
            updateEditModeToggleUi();
            const currentKey = getCurrentViewKey();
            const view = getViewDefinition(currentKey) || overviewTree.root;
            stopNarration();
            updateNarrationButton(view);

            viewTitle.innerHTML = '';
            if (view.titleButton && view.titleSuffix) {
                const titleButton = document.createElement('button');
                titleButton.className = 'title-inline-button';
                titleButton.type = 'button';
                titleButton.textContent = view.titleButton.label;
                titleButton.setAttribute('aria-label', `${view.titleButton.label} details`);
                titleButton.addEventListener('click', () => {
                    navigateToView(view.titleButton.target, `${currentKey}-title-button`);
                });
                viewTitle.appendChild(titleButton);
                viewTitle.appendChild(document.createTextNode(view.titleSuffix));
            } else {
                viewTitle.textContent = view.title || 'Test overview';
            }
            if (view.breadcrumbHtml) {
                viewBreadcrumb.innerHTML = view.breadcrumbHtml;
            } else {
                viewBreadcrumb.textContent = view.breadcrumb || '';
            }
            backButton.hidden = historyStack.length <= 1;
            overviewContent.innerHTML = '';

            if (currentKey === 'knowledge-root' && lastPraSelection) {
                renderInfoFrames([
                    {
                        title: 'Selected PRA focus',
                        copy: `${lastPraSelection.requirementLabel}, ${lastPraSelection.tone} ${lastPraSelection.score}`
                    }
                ]);
            }

            if (view.type === 'menu') {
                renderMenu(view);
                renderKnowledgeOverlapSection(view.expandableKnowledgeSection);
                renderExpandableActionSection(view.expandableActionSection);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'menu-with-intro') {
                if (view.intro) {
                    renderInfoFrames([view.intro]);
                }
                renderMenu(view);
                renderKnowledgeOverlapSection(view.expandableKnowledgeSection);
                renderExpandableActionSection(view.expandableActionSection);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'matrix') {
                renderMatrix(view);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'ai-types') {
                renderAiTypes(view);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'dtap') {
                renderDtap(view);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'requirement-reason-map') {
                renderRequirementReasonMap(view);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'structured-chaos') {
                renderStructuredChaos(view);
                renderViewAddOns(currentKey, view);
                maybeAutoplayNarration(view, currentKey);
                return;
            }

            if (view.type === 'info' || view.type === 'custom') {
                renderInfoFrames(view.frames);
            }

            renderViewAddOns(currentKey, view);
            maybeAutoplayNarration(view, currentKey);
        }

        backButton.addEventListener('click', () => {
            if (historyStack.length <= 1) return;
            playOverviewButtonSound(getCurrentViewKey(), 'back');
            historyStack.pop();
            renderCurrentView();
        });

        narrationButton.addEventListener('click', () => {
            if (narrationButton.disabled || !activeNarrationText) {
                return;
            }

            if (narrationState === 'playing') {
                pauseNarration();
                return;
            }

            if (narrationState === 'paused') {
                resumeNarration();
                return;
            }

            playOverviewButtonSound(`${getCurrentViewKey()}-narration`, 'forward');
            playNarration(activeNarrationText);
        });

        autoplayToggle.addEventListener('click', () => {
            isNarrationAutoplayEnabled = !isNarrationAutoplayEnabled;
            saveNarrationAutoplayState();
            updateAutoplayToggleUi();

            if (isNarrationAutoplayEnabled) {
                playOverviewButtonSound(`${getCurrentViewKey()}-autoplay-on`, 'forward');
                if (!narrationButton.disabled) {
                    if (narrationState === 'paused') {
                        resumeNarration();
                    } else if (narrationState === 'idle' && activeNarrationText) {
                        playNarration(activeNarrationText);
                    }
                }
                return;
            }

            playOverviewButtonSound(`${getCurrentViewKey()}-autoplay-off`, 'back');
        });

        editModeToggle.addEventListener('click', () => {
            isEditMode = !isEditMode;
            saveEditModeState();
            if (isEditMode) {
                playOverviewVictorySound(`${getCurrentViewKey()}-edit-mode-on`);
            } else {
                playOverviewButtonSound(`${getCurrentViewKey()}-edit-mode-off`, 'back');
                sanitizeHistoryForMode();
            }
            renderCurrentView();
        });

        window.addEventListener('beforeunload', stopNarration);
        if (supportsSpeechSynthesis) {
            window.speechSynthesis.addEventListener?.('voiceschanged', () => {
                if (narrationState !== 'idle') {
                    return;
                }
                const currentView = getViewDefinition(getCurrentViewKey()) || overviewTree.root;
                updateNarrationButton(currentView);
            });
        }

        updateAutoplayToggleUi();
        applyPageTheme(currentDtapThemeKey);
        renderCurrentView();
    </script>
</body>
</html>
