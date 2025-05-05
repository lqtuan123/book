@extends('frontend.layouts.master')

@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
@endphp

@section('title', $book->title . ' - Đọc Sách')

@section('css')
<style>
    :root {
        --bg-color: #f8f9fa;
        --container-bg: #fff;
        --text-color: #333;
        --secondary-text: #666;
        --border-color: #eee;
        --button-bg: #3490dc;
        --button-hover: #2779bd;
        --button-text: #fff;
        --shadow-color: rgba(0,0,0,0.05);
        --back-button-bg: #6c757d;
        --back-button-hover: #5a6268;
        --search-highlight: rgba(255, 230, 0, 0.4);
        --search-selected: rgba(255, 165, 0, 0.5);
    }

    body.dark-mode {
        --bg-color: #1a1a1a;
        --container-bg: #2c2c2c;
        --text-color: #f0f0f0;
        --secondary-text: #ccc;
        --border-color: #444;
        --button-bg: #2779bd;
        --button-hover: #1d5a8c;
        --button-text: #fff;
        --shadow-color: rgba(0,0,0,0.2);
        --back-button-bg: #505050;
        --back-button-hover: #656565;
        --search-highlight: rgba(255, 215, 0, 0.3);
        --search-selected: rgba(255, 140, 0, 0.4);
    }
    
    body {
        background-color: var(--bg-color);
        transition: background-color 0.3s;
    }
    
    .reader-container {
        max-width: 90%;
        margin: 0 auto;
        background: var(--container-bg);
        padding: 15px;
        box-shadow: 0 0 10px var(--shadow-color);
        border-radius: 4px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }
    
    .book-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .book-title {
        font-size: 24px;
        font-weight: bold;
        color: var(--text-color);
        margin-bottom: 5px;
        transition: color 0.3s;
    }
    
    .book-author {
        font-size: 14px;
        color: var(--secondary-text);
        transition: color 0.3s;
    }
    
    .pdf-controls {
        position: sticky;
        top: 70px;
        background: var(--container-bg);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-align: center;
        z-index: 999;
        transition: transform 0.35s cubic-bezier(0.215, 0.610, 0.355, 1.000), 
                    opacity 0.35s cubic-bezier(0.215, 0.610, 0.355, 1.000);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        width: 100%;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        will-change: transform, opacity;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    
    .pdf-controls.hidden {
        transform: translate3d(0, -150%, 0);
        opacity: 0;
        pointer-events: none;
    }
    
    .pdf-pagination {
        grid-column: 2;
        justify-self: center;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .actions-group {
        grid-column: 3;
        justify-self: end;
    }
    
    .pdf-pagination-info {
        font-size: 14px;
        color: var(--text-color);
        transition: color 0.3s;
    }
    
    .pdf-button {
        background: var(--button-bg);
        color: var(--button-text);
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    
    .pdf-button:hover {
        background: var(--button-hover);
    }
    
    .pdf-button:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .pdf-content {
        position: relative;
        margin: 0 auto;
        width: 100%;
    }
    
    .pdf-page {
        position: relative;
        margin-bottom: 30px;
        margin-left: auto;
        margin-right: auto;
        background-color: var(--container-bg);
        box-shadow: 0 4px 10px var(--shadow-color);
        transition: background-color 0.3s, box-shadow 0.3s;
        padding-bottom: 15px;
        border-bottom: 1px dashed var(--border-color);
        border-radius: 5px;
        overflow: hidden;
    }
    
    .loading-indicator {
        text-align: center;
        padding: 20px;
        color: var(--text-color);
        transition: color 0.3s;
    }
    
    .loading-spinner {
        border: 4px solid var(--border-color);
        border-top: 4px solid var(--button-bg);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .zoom-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        background-color: var(--back-button-bg);
        color: #fff;
        border: none;
        padding: 8px 15px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-bottom: 15px;
    }
    
    .back-button i {
        margin-right: 5px;
    }
    
    .back-button:hover {
        background-color: var(--back-button-hover);
    }
    
    /* PDF text layer styles */
    .pdf-textLayer {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        overflow: hidden;
        opacity: 0.2;
        line-height: 1.0;
    }
    
    .pdf-textLayer > span {
        color: transparent;
        position: absolute;
        white-space: pre;
        cursor: text;
        transform-origin: 0% 0%;
    }
    
    .pdf-textLayer .highlight {
        margin: -1px;
        padding: 1px;
        background-color: var(--search-highlight);
        border-radius: 4px;
    }
    
    .pdf-textLayer .highlight.selected {
        background-color: var(--search-selected);
    }
    
    /* PDF annotation layer styles */
    .pdf-annotationLayer section {
        position: absolute;
    }
    
    /* PDF canvas */
    .pdf-canvas {
        width: 100%;
        height: auto;
        border: none;
        direction: ltr;
    }

    /* Search panel */
    .search-panel {
        background: var(--container-bg);
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        border: 1px solid var(--border-color);
    }

    .search-input {
        flex: 1;
        min-width: 200px;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 14px;
        color: var(--text-color);
        background-color: var(--container-bg);
    }

    .search-results {
        color: var(--secondary-text);
        font-size: 14px;
        white-space: nowrap;
    }

    .utility-controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .dark-mode-toggle {
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        user-select: none;
        color: var(--text-color);
    }

    .toggle-switch {
        position: relative;
        width: 40px;
        height: 20px;
        background-color: #ccc;
        border-radius: 20px;
        transition: background-color 0.3s;
    }

    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 16px;
        height: 16px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s;
    }

    .dark-mode .toggle-switch {
        background-color: var(--button-bg);
    }

    .dark-mode .toggle-switch::after {
        transform: translateX(20px);
    }
    
    @media (max-width: 992px) {
        .reader-container {
            max-width: 95%;
            padding: 10px;
        }
        
        .pdf-controls {
            grid-template-columns: 1fr;
            grid-template-rows: auto auto auto;
            gap: 10px;
        }
        
        .pdf-pagination, .actions-group {
            grid-column: 1;
            justify-self: center;
        }

        .search-panel {
            flex-direction: column;
            align-items: flex-start;
        }

        .search-input {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .reader-container {
            max-width: 100%;
            padding: 8px;
        }

        .book-title {
            font-size: 20px;
        }

        .pdf-pagination-info {
            font-size: 12px;
        }

        .pdf-button {
            padding: 5px 10px;
            font-size: 12px;
        }
    }

    @media (max-width: 768px) {
        .pdf-controls {
            grid-template-columns: 1fr;
            grid-template-rows: auto auto;
            gap: 15px;
            padding: 10px;
        }
        
        .pdf-pagination, .actions-group {
            grid-column: 1;
            justify-self: center;
            width: 100%;
        }
        
        .pdf-pagination {
            justify-content: space-between;
        }
        
        .settings-content {
            right: -50px;
            width: 250px;
        }
        
        .bookmark-btn, .settings-btn {
            padding: 8px 15px;
            font-size: 18px;
        }
        
        .actions-group {
            margin-top: 5px;
        }

        .pdf-page {
            margin-bottom: 20px;
            box-shadow: 0 2px 6px var(--shadow-color);
        }
    }

    .reading-stats {
        position: fixed;
        bottom: 15px;
        right: 15px;
        background: var(--container-bg);
        border: none;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        z-index: 1000;
        max-width: 160px;
        transition: all 0.3s ease-in-out;
        font-size: 12px;
        color: var(--text-color);
        transform: translateY(0);
        opacity: 0.85;
    }
    
    .reading-stats.minimized {
        transform: translateY(calc(100% - 30px));
        cursor: pointer;
        opacity: 0.75;
    }
    
    .reading-stats.minimized:hover {
        opacity: 1;
    }
    
    .reading-stats-title {
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .reading-stats-title:before {
        content: '\f02d';
        font-family: 'Font Awesome 5 Free';
        margin-right: 5px;
        font-size: 12px;
        color: var(--button-bg);
    }
    
    .reading-stats-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        padding-bottom: 4px;
        border-bottom: 1px dashed var(--border-color);
    }
    
    .reading-stats-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .reading-stats-item span:first-child {
        color: var(--secondary-text);
        font-size: 11px;
    }
    
    .reading-stats-item span:last-child {
        font-weight: 600;
        color: var(--button-bg);
        font-size: 12px;
    }
    
    .reading-stats-close {
        cursor: pointer;
        font-size: 14px;
        color: var(--secondary-text);
        transition: color 0.2s;
        height: 20px;
        display: flex;
        align-items: center;
    }
    
    .reading-stats-close:hover {
        color: var(--button-bg);
    }
    
    .reading-stats-close:after {
        content: '\f106';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
    }
    
    .reading-stats.minimized .reading-stats-close:after {
        content: '\f107';
    }
    
    .point-alert {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background: var(--button-bg);
        color: var(--button-text);
        padding: 12px 18px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 1001;
        max-width: 300px;
        display: flex;
        align-items: center;
        font-weight: 500;
        transform: translateY(100px);
        opacity: 0;
        animation: point-alert-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards,
                   point-alert-out 0.5s ease-in forwards 4s;
    }

    .point-alert:before {
        content: '\f005';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-right: 10px;
        font-size: 16px;
        color: #FFD700;
    }
    
    @keyframes slide-in-left {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fade-out {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    @keyframes point-alert-in {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes point-alert-out {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(-20px); opacity: 0; }
    }

    .points-increase {
        animation: pulse-points 0.6s ease;
        color: #4CAF50;
    }

    @keyframes pulse-points {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    /* Thêm tooltip css */
    .tooltip {
        position: relative;
    }
    
    .tooltip:after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 3px;
        white-space: nowrap;
        font-size: 12px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .tooltip:hover:after {
        opacity: 1;
        visibility: visible;
    }

    /* Thêm CSS cho ẩn/hiện UI khi cuộn trang */
    .header-container {
        position: relative;
        background: var(--container-bg);
        z-index: 1000;
        transition: transform 0.35s cubic-bezier(0.215, 0.610, 0.355, 1.000), 
                    box-shadow 0.3s ease;
        box-shadow: 0 2px 10px var(--shadow-color);
        margin-bottom: 15px;
        border-radius: 0 0 10px 10px;
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    
    .header-container.hidden {
        transform: translate3d(0, -100%, 0);
        pointer-events: none;
    }

    /* Thêm CSS cho control-wrapper */
    .control-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
        margin: 0 auto;
    }

    /* Thêm CSS mới để ẩn toàn bộ giao diện người dùng khi cuộn xuống */
    @media (min-width: 768px) {
        body.hide-ui header.sticky {
            transform: translateY(-100%);
        }
        
        body.hide-ui .pdf-controls {
            transform: translateY(-150%);
            opacity: 0;
        }
    }

    // Thêm dòng này ngay đầu phần CSS để ẩn header khi cuộn
    header.sticky.top-0.z-40 {
        transition: transform 0.3s ease;
    }

    body.hide-ui header.sticky.top-0.z-40 {
        transform: translate3d(0, -100%, 0);
        will-change: transform;
        transition: transform 0.35s cubic-bezier(0.215, 0.610, 0.355, 1.000);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        pointer-events: none;
    }

    // Thêm CSS để điều chỉnh layout phần control-wrapper
    .control-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
        width: 100%;
        padding: 5px;
    }

    // Thêm CSS để đảm bảo nút hiển thị rõ ràng
    .reader-container .bookmark-btn i, 
    .reader-container .settings-btn i {
        font-size: 16px !important;
        pointer-events: none;
    }

    // Thêm CSS đặc biệt cho màn hình nhỏ
    @media (max-width: 768px) {
        .reader-container .bookmark-btn, 
        .reader-container .settings-btn {
            padding: 8px 15px !important;
            margin-top: 8px !important;
        }
        
        .control-wrapper {
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .pdf-pagination {
            width: 100%;
            justify-content: space-between;
            margin-bottom: 8px;
        }
    }

    // Chỉnh lại layout cho nút bookmark và settings
    @media (min-width: 768px) {
        .control-wrapper {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
        
        .pdf-pagination {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 10px;
        }
    }

    // Thêm CSS cho thông báo trạng thái
    .reader-status {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 15px 30px;
        border-radius: 30px;
        z-index: 2000;
        font-size: 18px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .reader-status.show {
        opacity: 1;
        visibility: visible;
    }

    // Thêm prefers-reduced-motion
    @media (prefers-reduced-motion: reduce) {
        .header-container,
        .pdf-controls,
        body.hide-ui header.sticky.top-0.z-40 {
            transition-duration: 0.1s;
            transition-timing-function: ease-in-out;
        }
    }

    // Thêm CSS cho số trang
    .page-number {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    // Thêm CSS mới để hiển thị phần đánh dấu trang hiện tại
    .pdf-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background-color: var(--button-bg);
        opacity: 0.7;
        border-radius: 5px 0 0 5px;
    }

    .book-header {
        transition: all 0.3s ease;
    }
    
    .book-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.3rem;
        transition: color 0.3s;
        line-height: 1.3;
    }
    
    .book-author {
        font-size: 0.9rem;
        color: var(--secondary-text);
        transition: color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (min-width: 768px) {
        .book-author {
            justify-content: flex-end;
        }
        
        .book-title {
            font-size: 1.75rem;
        }
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        background-color: var(--back-button-bg);
        color: #fff;
        border: none;
        padding: 8px 15px;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        text-decoration: none;
    }
    
    .back-button i {
        margin-right: 5px;
        transition: transform 0.2s;
    }
    
    .back-button:hover {
        background-color: var(--back-button-hover);
        color: #fff;
        transform: translateY(-2px);
    }
    
    .back-button:hover i {
        transform: translateX(-3px);
    }
    
    .back-button:active {
        transform: translateY(0);
    }
    
    /* Dark mode adjustments */
    body.dark-mode .header-container {
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
    }
    
    .header-container {
        position: relative;
        background: var(--container-bg);
        z-index: 1000;
        transition: transform 0.35s cubic-bezier(0.215, 0.610, 0.355, 1.000), 
                    box-shadow 0.3s ease;
        box-shadow: 0 2px 10px var(--shadow-color);
        margin-bottom: 15px;
        border-radius: 0 0 10px 10px;
        will-change: transform;
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    
    .header-container.hidden {
        transform: translate3d(0, -100%, 0);
        pointer-events: none;
    }
    
    .book-header {
        transition: all 0.3s ease;
    }
    
    .book-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-color);
        transition: color 0.3s;
        line-height: 1.3;
    }
    
    @media (min-width: 768px) {
        .book-title {
            font-size: 2rem;
        }
    }
    
    /* Dark mode adjustments */
    body.dark-mode .header-container {
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container-fluid reader-container py-4 px-0">
    <div class="header-container shadow-sm rounded-bottom" id="header-container">
        <div class="d-flex justify-content-center p-2">
            <div class="book-header text-center">
                <h1 class="book-title mb-0">{{ $book->title }}</h1>
            </div>
        </div>
    </div>
    
    <div id="pdf-controls" class="pdf-controls">
        <button id="prev-page" class="pdf-button" disabled>
            <i class="fas fa-chevron-left mr-1"></i> Trang trước
        </button>
        
        <span id="page-info" class="pdf-pagination-info">
            Trang <span id="current-page">-</span> / <span id="total-pages">-</span>
        </span>
        
        <button id="next-page" class="pdf-button" disabled>
            Trang sau <i class="fas fa-chevron-right ml-1"></i>
        </button>
        
        <button id="bookmark-btn" style="width: 38px; height: 38px; background-color: #f8f9fa; color: #dc3545; border: 1px solid #dee2e6; border-radius: 4px; margin: 0 5px; padding: 0; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; position: relative; z-index: 1000;">
            <i class="far fa-heart"></i>
        </button>
        
        <div style="position: relative; display: inline-block;">
            <button id="settings-btn" style="width: 38px; height: 38px; background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; border-radius: 4px; margin: 0 5px; padding: 0; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; position: relative; z-index: 1000;">
                <i class="fas fa-cog"></i>
            </button>
            
            <div id="settings-content" style="display: none; position: absolute; right: 0; background: #f8f9fa; min-width: 180px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); z-index: 1001; border-radius: 4px; padding: 5px; margin-top: 5px; border: 1px solid #dee2e6;">
                <div style="padding: 6px; border-bottom: 1px solid #dee2e6;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin: 2px 0;">
                        <button id="zoom-out" style="border: none; background: #f1f3f5; color: #212529; padding: 3px 6px; border-radius: 3px; cursor: pointer; font-size: 12px;" disabled>-</button>
                        <span id="zoom-info" style="font-size: 12px; color: #495057;">75%</span>
                        <button id="zoom-in" style="border: none; background: #f1f3f5; color: #212529; padding: 3px 6px; border-radius: 3px; cursor: pointer; font-size: 12px;" disabled>+</button>
                    </div>
                </div>
                <div style="padding: 6px; border-bottom: 1px solid #dee2e6;">
                    <label class="dark-mode-switch" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                        <span style="font-size: 13px; color: #495057;">Chế độ tối</span>
                        <input type="checkbox" id="dark-mode-toggle" style="display: none;">
                        <div class="toggle" style="width: 32px; height: 16px; background-color: #dee2e6; border-radius: 10px; position: relative; transition: background-color 0.3s ease;">
                            <div class="toggle-handle" style="width: 12px; height: 12px; background-color: white; border-radius: 50%; position: absolute; top: 2px; left: 2px; transition: transform 0.3s ease;"></div>
                        </div>
                    </label>
                </div>
                <div style="padding: 6px;">
                    <button id="stats-toggle" style="width: 100%; border: none; background: #f1f3f5; color: #212529; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 13px;">Hiện thống kê</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="loading-indicator" class="loading-indicator">
        <div class="loading-spinner"></div>
        <p>Đang tải sách...</p>
    </div>
    
    @if($resources->count() > 0)
        <?php $pdfFound = false; ?>
        <div id="pdf-content" class="pdf-content"></div>
        
        <script>
            // Danh sách tất cả tài nguyên PDF để hiển thị
            const pdfResources = [
                @foreach($resources as $resource)
                    @if(Str::endsWith($resource->url, '.pdf') || $resource->file_type == 'application/pdf')
                        '{{ asset($resource->url) }}',
                        <?php $pdfFound = true; ?>
                    @endif
                @endforeach
            ];
            
            // Biến để lưu URL PDF hiện tại đang hiển thị
            let currentPdfUrl = '';
            
            // Nếu có tài nguyên PDF, lưu URL đầu tiên
            if (pdfResources.length > 0) {
                currentPdfUrl = pdfResources[0];
            }
        </script>
        
        @if(!$pdfFound)
            <div class="alert alert-warning">
                Không tìm thấy tài liệu PDF cho sách này.
            </div>
        @endif
    @else
        <div class="alert alert-warning">
            Không tìm thấy tài liệu cho sách này.
        </div>
    @endif
    
    <!-- Thống kê thời gian đọc -->
    <div id="reading-stats" class="reading-stats @if(!Auth::check()) d-none @endif">
        <div class="reading-stats-title">
            Tiến độ
            <span class="reading-stats-close" onclick="toggleReadingStats()"></span>
        </div>
        <div class="reading-stats-item">
            <span>Đọc:</span>
            <span id="total-reading-time">0 phút</span>
        </div>
        <div class="reading-stats-item">
            <span>Điểm:</span>
            <span id="points-earned">0</span>
        </div>
    </div>

    <!-- Thông báo trạng thái đọc sách -->
    <div id="reader-status" class="reader-status"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    // Đặt worker path cho PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Nếu không có PDF nào để hiển thị, dừng lại
        if (!currentPdfUrl) {
            const loadingIndicator = document.getElementById('loading-indicator');
            loadingIndicator.innerHTML = '<p class="text-danger">Không tìm thấy tài liệu PDF cho sách này.</p>';
            return;
        }
        
        // Các tham chiếu đến các phần tử DOM
        const pdfContent = document.getElementById('pdf-content');
        const loadingIndicator = document.getElementById('loading-indicator');
        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const currentPageSpan = document.getElementById('current-page');
        const totalPagesSpan = document.getElementById('total-pages');
        const zoomInBtn = document.getElementById('zoom-in');
        const zoomOutBtn = document.getElementById('zoom-out');
        const zoomLevelSpan = document.getElementById('zoom-info');
        const settingsBtn = document.getElementById('settings-btn');
        const settingsContent = document.getElementById('settings-content');
        const bookmarkBtn = document.getElementById('bookmark-btn');
        const statsToggleBtn = document.getElementById('stats-toggle');
        
        let currentPdf = null;
        let currentPage = 1;
        let currentScale = 0.75; // Tỉ lệ zoom mặc định giảm xuống 75%
        let isRendering = false;
        let pageNumPending = null;
        
        // Tìm biến pagesPerView và thay đổi từ 1 thành 5
        const pagesPerView = 5;
        
        // Tải PDF
        pdfjsLib.getDocument(currentPdfUrl).promise.then(pdf => {
            currentPdf = pdf;
            totalPagesSpan.textContent = pdf.numPages;
            
            // Kích hoạt các nút điều khiển
            prevPageBtn.disabled = false;
            nextPageBtn.disabled = false;
            zoomInBtn.disabled = false;
            zoomOutBtn.disabled = false;
            
            // Hiển thị trang đầu tiên
            renderPages(1);
            
            // Kích hoạt nút chuyển trang - version mới với xử lý rõ ràng
            nextPageBtn.onclick = function() {
                console.log('Next page clicked, current page:', currentPage, 'total pages:', pdf.numPages);
                if (currentPage + pagesPerView <= pdf.numPages) {
                    currentPage += pagesPerView;
                    queueRenderPages(currentPage);
                } else if (currentPage < pdf.numPages) {
                    // Trường hợp còn lại ít hơn pagesPerView trang
                    currentPage = Math.max(1, pdf.numPages - pagesPerView + 1);
                    queueRenderPages(currentPage);
                }
            };
            
            prevPageBtn.onclick = function() {
                console.log('Previous page clicked, current page:', currentPage);
                if (currentPage > pagesPerView) {
                    currentPage -= pagesPerView;
                    queueRenderPages(currentPage);
                } else if (currentPage > 1) {
                    currentPage = 1;
                    queueRenderPages(currentPage);
                }
            };
            
            // Xử lý phóng to thu nhỏ - version mới đảm bảo hoạt động
            zoomInBtn.onclick = function() {
                console.log('Zoom in clicked, current scale:', currentScale);
                if (currentScale < 3.0) {
                    currentScale += 0.25;
                    updateZoomLevel();
                    queueRenderPages(currentPage);
                    showStatus(`Phóng to ${Math.round(currentScale * 100)}%`, 'search-plus');
                }
            };
            
            zoomOutBtn.onclick = function() {
                console.log('Zoom out clicked, current scale:', currentScale);
                if (currentScale > 0.5) {
                    currentScale -= 0.25;
                    updateZoomLevel();
                    queueRenderPages(currentPage);
                    showStatus(`Thu nhỏ ${Math.round(currentScale * 100)}%`, 'search-minus');
                }
            };
            
            // Bắt sự kiện phím
            document.addEventListener('keydown', (e) => {
                if (e.code === 'ArrowLeft' && currentPage > 1) {
                    if (currentPage > pagesPerView) {
                        currentPage -= pagesPerView;
                    } else {
                        currentPage = 1;
                    }
                    queueRenderPages(currentPage);
                } else if (e.code === 'ArrowRight' && currentPage < pdf.numPages) {
                    if (currentPage + pagesPerView <= pdf.numPages) {
                        currentPage += pagesPerView;
                    } else {
                        currentPage = Math.max(1, pdf.numPages - pagesPerView + 1);
                    }
                    queueRenderPages(currentPage);
                }
            });
            
            // Xử lý sự kiện resize window
            window.addEventListener('resize', debounce(() => {
                if (currentPage && currentPdf) {
                    queueRenderPages(currentPage);
                }
            }, 250));
            
        }).catch(error => {
            console.error('Lỗi khi tải PDF:', error);
            loadingIndicator.innerHTML = `<p class="text-danger">Lỗi khi tải PDF: ${error.message}</p>`;
        });
        
        // Hàm cập nhật hiển thị mức zoom
        function updateZoomLevel() {
            zoomLevelSpan.textContent = `${Math.round(currentScale * 100)}%`;
        }
        
        // Hàm xếp hàng đợi render trang
        function queueRenderPages(startPage, callback) {
            if (isRendering) {
                pageNumPending = startPage;
            } else {
                renderPages(startPage, callback);
            }
        }
        
        // Hàm render các trang PDF
        async function renderPages(startPage, callback) {
            isRendering = true;
            
            // Cập nhật UI
            currentPageSpan.textContent = startPage + " - " + Math.min(startPage + pagesPerView - 1, currentPdf.numPages);
            prevPageBtn.disabled = startPage === 1;
            nextPageBtn.disabled = startPage + pagesPerView - 1 >= currentPdf.numPages;
            
            // Hiển thị loading
            loadingIndicator.style.display = 'block';
            
            // Xóa nội dung hiện tại
            pdfContent.innerHTML = '';
            
            // Số trang cần hiển thị (không vượt quá tổng số trang)
            const pagesToRender = Math.min(pagesPerView, currentPdf.numPages - startPage + 1);
            
            const renderPromises = [];
            let renderedCount = 0;
            
            try {
                // Tạo và render từng trang
            for (let i = 0; i < pagesToRender; i++) {
                const pageNumber = startPage + i;
                    
                    // Tạo container cho trang hiện tại
                    const pageContainer = document.createElement('div');
                    pageContainer.className = 'pdf-page page-transition';
                    pageContainer.setAttribute('data-page-number', pageNumber);
                    
                    // Thêm placeholder hiển thị số trang
                    const pageNumberElem = document.createElement('div');
                    pageNumberElem.className = 'page-number';
                    pageNumberElem.textContent = pageNumber;
                    pageContainer.appendChild(pageNumberElem);
                    
                    // Hiển thị loading cho trang
                    const loadingElem = document.createElement('div');
                    loadingElem.className = 'loading-indicator page-loading';
                    loadingElem.innerHTML = '<div class="loading-spinner"></div><p>Đang tải trang ' + pageNumber + '...</p>';
                    pageContainer.appendChild(loadingElem);
                    
                    pdfContent.appendChild(pageContainer);
                    
                    // Thêm promise cho việc render trang
                    renderPromises.push(
                        renderSinglePage(pageNumber, pageContainer)
                            .then(() => {
                                renderedCount++;
                                // Cập nhật tiến độ loading (có thể thêm nếu cần)
                            })
                            .catch(error => {
                                console.error(`Lỗi khi render trang ${pageNumber}:`, error);
                            })
                    );
                }
                
                // Đợi tất cả các trang được render xong
                await Promise.all(renderPromises);
                
                // Ẩn loading indicator
                loadingIndicator.style.display = 'none';
                
                // Gọi callback nếu có
                if (typeof callback === 'function') {
                    callback();
                }
            } catch (error) {
                console.error('Lỗi khi render các trang:', error);
                loadingIndicator.innerHTML = `<p class="text-danger">Lỗi khi render trang: ${error.message}</p>`;
                loadingIndicator.style.display = 'block'; // Đảm bảo hiển thị thông báo lỗi
            } finally {
                // Luôn đánh dấu render hoàn tất kể cả có lỗi
            isRendering = false;
            
            // Kiểm tra xem có trang nào trong hàng đợi không
            if (pageNumPending !== null) {
                const pendingPage = pageNumPending;
                pageNumPending = null;
                    setTimeout(() => renderPages(pendingPage), 100); // Thêm timeout để tránh đệ quy vô hạn
                }
            }
        }
        
        // Hàm render một trang đơn
        async function renderSinglePage(pageNumber, existingContainer = null) {
            try {
                // Lấy trang PDF
                const page = await currentPdf.getPage(pageNumber);
                
                // Nếu đã có container, sử dụng nó
                let pageContainer = existingContainer;
                
                // Tính toán kích thước viewport để phù hợp với chiều rộng container
                const containerWidth = pdfContent.clientWidth;
                const originalViewport = page.getViewport({ scale: 1.0 });
                
                // Tính toán tỉ lệ để vừa với container
                const containerScale = containerWidth / originalViewport.width;
                const finalScale = containerScale * currentScale;
                
                // Tạo viewport với tỉ lệ mới
                const viewport = page.getViewport({ scale: finalScale });
                
                // Thêm kích thước cho container
                pageContainer.style.width = `${viewport.width}px`;
                pageContainer.style.height = `${viewport.height}px`;
                
                // Tạo canvas cho trang
                const canvas = document.createElement('canvas');
                canvas.className = 'pdf-canvas';
                const context = canvas.getContext('2d');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                
                // Thêm canvas vào container trang
                pageContainer.appendChild(canvas);
                
                // Tạo div cho text layer nếu chưa có
                let textLayerDiv = pageContainer.querySelector('.pdf-textLayer');
                if (!textLayerDiv) {
                    textLayerDiv = document.createElement('div');
                textLayerDiv.className = 'pdf-textLayer';
                textLayerDiv.style.width = `${viewport.width}px`;
                textLayerDiv.style.height = `${viewport.height}px`;
                pageContainer.appendChild(textLayerDiv);
                }
                
                // Xóa các phần tử loading nếu có
                const loading = pageContainer.querySelector('.page-loading');
                if (loading) {
                    loading.remove();
                }
                
                // Render trang vào canvas
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                
                await page.render(renderContext).promise;
                
                // Lấy nội dung text và render text layer
                const textContent = await page.getTextContent();
                
                // Sử dụng text layer builder từ PDF.js
                await pdfjsLib.renderTextLayer({
                    textContent: textContent,
                    container: textLayerDiv,
                    viewport: viewport,
                    textDivs: []
                }).promise;
                
                // Đánh dấu trang đã được render
                pageContainer.setAttribute('data-rendered', 'true');
                
                return true;
            } catch (error) {
                console.error(`Lỗi khi render trang ${pageNumber}:`, error);
                throw error;
            }
        }
        
        // Function để delay việc thực thi callback (tránh gọi quá nhiều khi resize)
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    func.apply(context, args);
                }, wait);
            };
        }

        // Toggle settings dropdown
        settingsBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (settingsContent.style.display === 'none' || !settingsContent.style.display) {
                settingsContent.style.display = 'block';
            } else {
                settingsContent.style.display = 'none';
            }
        });
        
        // Đóng settings dropdown khi click bên ngoài
        document.addEventListener('click', function(e) {
            if (!settingsBtn.contains(e.target) && !settingsContent.contains(e.target)) {
                settingsContent.style.display = 'none';
            }
        });
        
        // Toggle bookmark
        bookmarkBtn.addEventListener('click', function() {
            // Kiểm tra đăng nhập trước khi thực hiện
            @if(!Auth::check())
                window.location.href = "{{ route('front.login') }}";
                return;
            @endif
            
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.replace('far', 'fas');
                // Hiển thị thông báo
                showPointAlert('Đã thêm vào yêu thích');
                
                // Gọi API để lưu bookmark
                fetch('{{ route("front.book.bookmark") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        item_id: {{ $book->id }},
                        item_code: 'book'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Nếu có lỗi, đảo ngược trạng thái
                        icon.classList.replace('fas', 'far');
                        showPointAlert(data.msg || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    // Đảo ngược trạng thái nếu có lỗi
                    icon.classList.replace('fas', 'far');
                });
            } else {
                icon.classList.replace('fas', 'far');
                // Hiển thị thông báo
                showPointAlert('Đã xóa khỏi yêu thích');
                
                // Gọi API để xóa bookmark
                fetch('{{ route("front.book.bookmark") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        item_id: {{ $book->id }},
                        item_code: 'book'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Nếu có lỗi, đảo ngược trạng thái
                        icon.classList.replace('far', 'fas');
                        showPointAlert(data.msg || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    // Đảo ngược trạng thái nếu có lỗi
                    icon.classList.replace('far', 'fas');
                });
            }
        });
        
        // Toggle reading stats
        statsToggleBtn.addEventListener('click', function() {
            toggleReadingStats();
        });

        // Thêm đoạn code sau vào phần DOMContentLoaded
        // Xử lý hiển thị/ẩn UI khi cuộn trang
        let lastScrollY = window.scrollY;
        const headerContainer = document.getElementById('header-container');
        const pdfControls = document.getElementById('pdf-controls');
        let isScrolling;
        let lastScrollDirection = null;
        let scrollTimeStamp = Date.now();
        const scrollDelay = 50; // Thời gian delay để giảm số lần xử lý sự kiện scroll

        // Thêm biến để theo dõi trạng thái hiển thị UI
        let isUIToggling = false;  // Biến để theo dõi trạng thái đang chuyển đổi UI
        const uiToggleDelay = 500; // Khoảng thời gian tối thiểu giữa các lần chuyển đổi UI

        // Thêm vào phía trên isUIToggling
        let uiVisible = true;  // Biến để theo dõi trạng thái hiển thị/ẩn của UI

        // Sửa lại và thêm đầy đủ các hàm
        // Cập nhật lại hàm forceShowUI để không bị toggle quá nhanh
        function forceShowUI() {
            if (!uiVisible && !isUIToggling) {
                isUIToggling = true;
                requestAnimationFrame(() => {
                    headerContainer.classList.remove('hidden');
                    pdfControls.classList.remove('hidden');
                    document.body.classList.remove('hide-ui');
                    uiVisible = true;
                    
                    // Đặt lại trạng thái sau khi chuyển đổi xong
                    setTimeout(() => {
                        isUIToggling = false;
                    }, uiToggleDelay);
                });
            }
        }

        function forceHideUI() {
            if (uiVisible && !isUIToggling) {
                isUIToggling = true;
                requestAnimationFrame(() => {
                    headerContainer.classList.add('hidden');
                    pdfControls.classList.add('hidden');
                    document.body.classList.add('hide-ui');
                    uiVisible = false;
                    
                    // Đặt lại trạng thái sau khi chuyển đổi xong
                    setTimeout(() => {
                        isUIToggling = false;
                    }, uiToggleDelay);
                });
            }
        }

        // Thêm những hàm bị mất
        function showUI() {
            forceShowUI();
        }

        function hideUI() {
            forceHideUI();
        }

        // Thêm event listener mạnh mẽ hơn cho scroll event
        let scrollTimer;
        
        window.addEventListener('wheel', function(e) {
            clearTimeout(scrollTimer);
            
            // Chỉ phản ứng với các cử chỉ cuộn lớn hơn một ngưỡng nhất định
            const scrollDetectionThreshold = 20; // Tăng lên để giảm độ nhạy
            
            if (Math.abs(e.deltaY) < scrollDetectionThreshold) {
                // Bỏ qua các cử chỉ cuộn nhẹ
                return;
            }
            
            // Chỉ ẩn UI khi cuộn xuống, mức nhạy bình thường
            if (e.deltaY > scrollDetectionThreshold && window.scrollY > scrollThreshold) {
                forceHideUI();
            } 
            // Chỉ hiện UI khi cuộn mạnh lên trên - yêu cầu cuộn mạnh hơn nhiều
            else if (e.deltaY < -scrollDetectionThreshold * 3) {
                forceShowUI();
            }
            
            // Không tự hiện UI khi dừng cuộn ở giữa trang, chỉ hiện khi gần đầu trang
            scrollTimer = setTimeout(() => {
                if (window.scrollY < scrollThreshold / 2) { // Chỉ hiện khi rất gần đầu trang
                    forceShowUI();
                }
            }, 500); // Tăng thời gian timeout để không hiện quá nhanh
        }, { passive: true });

        // Đối với màn hình cảm ứng
        let touchStartY = 0;
        
        window.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        window.addEventListener('touchmove', function(e) {
            const touchY = e.touches[0].clientY;
            const diff = touchStartY - touchY;
            
            clearTimeout(scrollTimer);
            
            // Chỉ phản ứng khi vuốt đủ mạnh
            const touchDetectionThreshold = 15; // Ngưỡng phát hiện vuốt (giá trị lớn hơn = ít nhạy hơn)
            
            if (Math.abs(diff) < touchDetectionThreshold) {
                // Bỏ qua các cử chỉ vuốt nhẹ
                return;
            }
            
            if (diff > touchDetectionThreshold && window.scrollY > scrollThreshold) {
                // Vuốt lên mạnh (cuộn xuống)
                forceHideUI();
            } else if (diff < -touchDetectionThreshold * 2) {
                // Vuốt xuống mạnh (cuộn lên)
                // Yêu cầu vuốt mạnh hơn để hiện UI
                forceShowUI();
            }
            
            touchStartY = touchY;
            
            scrollTimer = setTimeout(() => {
                if (window.scrollY < scrollThreshold) {
                    forceShowUI();
                }
            }, 300); // Tăng thời gian timeout
        }, { passive: true });
    });

    // Biến để theo dõi thời gian đọc sách
    const bookId = {{ $book->id }};
    let isActive = true;
    let readingInterval;
    const updateInterval = 30000; // Gửi dữ liệu mỗi 30 giây
    let lastPointsEarned = 0;
    
    // Hiển thị thông báo nhận điểm
    function showPointAlert(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'point-alert';
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);
        
        // Tự động xóa thông báo sau 5 giây
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
    
    // Cập nhật thông tin thời gian đọc trên giao diện
    function updateReadingStatsUI(totalMinutes, pointsEarned) {
        document.getElementById('total-reading-time').textContent = totalMinutes.toFixed(1) + ' phút';
        
        // Nếu có điểm mới, hiển thị hiệu ứng
        if (pointsEarned > lastPointsEarned) {
            const pointsElement = document.getElementById('points-earned');
            const newPoints = pointsEarned - lastPointsEarned;
            
            // Thêm class để tạo hiệu ứng
            pointsElement.classList.add('points-increase');
            
            // Cập nhật giá trị điểm
            pointsElement.textContent = pointsEarned;
            
            // Hiển thị thông báo điểm thưởng
            showPointAlert(`+${newPoints} điểm thưởng! Tiếp tục đọc để nhận thêm.`);
            
            // Xóa class sau khi hiệu ứng kết thúc
            setTimeout(() => {
                pointsElement.classList.remove('points-increase');
            }, 600);
            
            lastPointsEarned = pointsEarned;
        } else {
            document.getElementById('points-earned').textContent = pointsEarned;
        }
    }
    
    // Ẩn/hiện thống kê thời gian đọc
    function toggleReadingStats() {
        const stats = document.getElementById('reading-stats');
        stats.classList.toggle('minimized');
    }
    
    // Hàm gửi thông tin về thời gian đọc sách lên server
    function updateReadingTime() {
        if (!isActive) return;
        
        // Kiểm tra đăng nhập trước khi gửi dữ liệu
        @if(!Auth::check())
            return; // Không cập nhật thời gian đọc nếu chưa đăng nhập
        @endif
        
        fetch('{{ route("books.update-reading-time") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                book_id: bookId,
                active: isActive
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Thời gian đọc được cập nhật:', data.total_minutes.toFixed(1), 'phút');
                console.log('Điểm đã nhận:', data.points_earned);
                
                // Cập nhật giao diện
                updateReadingStatsUI(data.total_minutes, data.points_earned);
            }
        })
        .catch(error => {
            console.error('Lỗi khi cập nhật thời gian đọc:', error);
        });
    }
    
    // Khi trang được tải, bắt đầu theo dõi thời gian đọc
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Khởi tạo hệ thống tính thời gian đọc sách");
        window.readingSessionEnded = false; // Đảm bảo bắt đầu với phiên mới
        
        @if(Auth::check())
        // Khởi tạo phiên đọc sách khi vào trang - chỉ khi đã đăng nhập
        setTimeout(function() {
            fetch('{{ route("books.start-reading") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    book_id: bookId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Đã bắt đầu phiên đọc sách mới');
                    console.log('Phiên hiện tại:', data.session_id);
                    
                    // Lưu ID phiên để dùng khi kết thúc
                    window.currentReadingSessionId = data.session_id;
                    
                    // Bắt đầu theo dõi thời gian đọc
                    readingInterval = setInterval(updateReadingTime, updateInterval);
                    
                    // Cập nhật lần đầu ngay khi phiên được tạo
                    setTimeout(updateReadingTime, 1000);
                }
            })
            .catch(error => {
                console.error('Lỗi khi khởi tạo phiên đọc:', error);
            });
        }, 1000);
        @endif
        
        // Theo dõi khi người dùng chuyển tab - chỉ dừng tính thời gian, không reset
        document.addEventListener('visibilitychange', function() {
            console.log("Visibility change:", document.hidden);
            isActive = !document.hidden;
            if (!window.readingSessionEnded) {
            updateReadingTime(); // Cập nhật ngay khi trạng thái thay đổi
            }
        });
        
        // Theo dõi khi user nhấn vào link nội bộ - sẽ reset session
        document.addEventListener('click', function(e) {
            // Kiểm tra xem có nhấp vào link nội bộ không
            const target = e.target.closest('a');
            if (target && target.href && !target.getAttribute('target') 
                && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                // Chỉ reset khi chuyển trang nội bộ (không phải mở tab mới)
                // và không phải click chuột phải, tức là sẽ rời khỏi trang hiện tại
                console.log("Chuyển sang trang khác trong web, reset phiên đọc");
                finishReading();
            }
        });
        
        // Khi người dùng rời khỏi trang - reset phiên đọc
        window.addEventListener('beforeunload', function() {
            console.log("Thoát trang, reset phiên đọc");
            finishReading();
        });
        
        // Lưu thời điểm cuối cùng người dùng tương tác với trang
        let lastInteractionTime = Date.now();
        const interactionEvents = ['click', 'touchstart', 'mousemove', 'keydown', 'scroll'];
        
        // Theo dõi tương tác người dùng
        interactionEvents.forEach(event => {
            document.addEventListener(event, function() {
                if (!window.readingSessionEnded) {
                    lastInteractionTime = Date.now();
                    // Nếu trước đó đã không hoạt động, kích hoạt lại
                    if (!isActive) {
                        console.log("Phát hiện tương tác, tiếp tục tính thời gian");
                        isActive = true;
                        updateReadingTime();
                    }
                }
            }, { passive: true });
        });
        
        // Kiểm tra định kỳ nếu người dùng không tương tác trong thời gian dài (ví dụ: 5 phút)
        const inactivityCheckInterval = 60000; // kiểm tra mỗi phút
        const inactivityThreshold = 300000; // 5 phút
        
        setInterval(() => {
            if (!window.readingSessionEnded && isActive && Date.now() - lastInteractionTime > inactivityThreshold) {
                console.log("Không hoạt động trong 5 phút, dừng tính thời gian");
                isActive = false;
                updateReadingTime();
            }
        }, inactivityCheckInterval);
    });
    
    /**
     * Kết thúc phiên đọc sách
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function finishReading() {
        console.log("Kết thúc phiên đọc sách");
        
        // Nếu đã gọi finishReading rồi, không gọi lại nữa
        if (window.readingSessionEnded) {
            console.log("Phiên đọc đã kết thúc trước đó, bỏ qua");
            return;
        }
        
        console.log("⚠️ Kết thúc phiên đọc sách ⚠️");
        
        // Đánh dấu đã kết thúc phiên ngay lập tức để tránh gọi lại
        window.readingSessionEnded = true;
        
        // Xóa interval nếu còn tồn tại
        if (readingInterval) {
            clearInterval(readingInterval);
            readingInterval = null;
        }
        
        @if(!Auth::check())
            return; // Không gửi dữ liệu nếu chưa đăng nhập
        @endif
        
        try {
            // Thử cả hai phương pháp để đảm bảo dữ liệu được gửi đi
            
            // 1. Sử dụng sendBeacon (không đồng bộ nhưng đáng tin cậy khi đóng trang)
            const beaconSent = navigator.sendBeacon(
                '{{ route("books.finish-reading") }}', 
                JSON.stringify({
                    book_id: bookId,
                    _token: '{{ csrf_token() }}'
                })
            );
            
            console.log("SendBeacon thành công:", beaconSent);
            
            // 2. Dự phòng: gửi fetch với keepalive nếu sendBeacon không thành công
            if (!beaconSent) {
                console.log("SendBeacon thất bại, thử phương pháp fetch");
                
                fetch('{{ route("books.finish-reading") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        book_id: bookId,
                        _token: '{{ csrf_token() }}'
                    }),
                    keepalive: true
                }).then(response => {
                    console.log("Fetch thành công:", response.ok);
                }).catch(error => {
                    console.error("Fetch thất bại:", error);
                });
            }
        } catch (error) {
            console.error("❌ Lỗi khi kết thúc phiên đọc:", error);
        }
        
        // Thông báo cho người dùng
        showStatus("Đã lưu tiến độ đọc", "check-circle");
    }

    // Hàm hiển thị trạng thái dạng toast
    function showStatus(message, icon = 'info-circle') {
        const statusDiv = document.getElementById('reader-status');
        statusDiv.innerHTML = `<i class="fas fa-${icon}"></i> ${message}`;
        statusDiv.classList.add('show');
        
        setTimeout(() => {
            statusDiv.classList.remove('show');
        }, 2000);
    }

    // Thêm đoạn mã này vào sau
    // Xử lý chế độ tối
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const toggleHandle = document.querySelector('.toggle-handle');
    const body = document.body;
    
    // Kiểm tra nếu user đã bật chế độ tối trước đó
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        darkModeToggle.checked = true;
        toggleHandle.style.transform = 'translateX(16px)';
        document.querySelector('.toggle').style.backgroundColor = '#007bff';
    }
    
    // Xử lý khi toggle chế độ tối
    darkModeToggle.addEventListener('change', function() {
        if (this.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'enabled');
            toggleHandle.style.transform = 'translateX(16px)';
            document.querySelector('.toggle').style.backgroundColor = '#007bff';
            showStatus('Đã bật chế độ tối', 'moon');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
            toggleHandle.style.transform = 'translateX(0)';
            document.querySelector('.toggle').style.backgroundColor = '#dee2e6';
            showStatus('Đã tắt chế độ tối', 'sun');
        }
    });

    // Cải thiện logic JavaScript để tăng độ nhạy khi cuộn trang
    let lastKnownScrollY = 0;
    let lastScrollY = 0;
    let scrollTimeStamp = Date.now();
    let isScrolling;
    let scrollDirection = '';
    let lastScrollDirection = '';
    let scrollDelay = 50; // Giảm delay để phát hiện cuộn nhanh hơn
    const scrollThreshold = 30; // Ngưỡng cuộn nhỏ hơn để phản ứng nhanh hơn

    // Thêm một hệ thống theo dõi lịch sử cuộn để giảm thiểu ẩn/hiện liên tục
    let isUIToggling = false;  // Biến để theo dõi trạng thái đang chuyển đổi UI
    const uiToggleDelay = 500; // Khoảng thời gian tối thiểu giữa các lần chuyển đổi UI

    // Thêm vào phía trên isUIToggling
    let uiVisible = true;  // Biến để theo dõi trạng thái hiển thị/ẩn của UI

    // Cập nhật lại hàm forceShowUI để không bị toggle quá nhanh
    function forceShowUI() {
        if (!uiVisible && !isUIToggling) {
            isUIToggling = true;
            requestAnimationFrame(() => {
                headerContainer.classList.remove('hidden');
                pdfControls.classList.remove('hidden');
                document.body.classList.remove('hide-ui');
                uiVisible = true;
                
                // Đặt lại trạng thái sau khi chuyển đổi xong
                setTimeout(() => {
                    isUIToggling = false;
                }, uiToggleDelay);
            });
        }
    }

    function forceHideUI() {
        if (uiVisible && !isUIToggling) {
            isUIToggling = true;
            requestAnimationFrame(() => {
                headerContainer.classList.add('hidden');
                pdfControls.classList.add('hidden');
                document.body.classList.add('hide-ui');
                uiVisible = false;
                
                // Đặt lại trạng thái sau khi chuyển đổi xong
                setTimeout(() => {
                    isUIToggling = false;
                }, uiToggleDelay);
            });
        }
    }

    // Thêm event listener mạnh mẽ hơn cho scroll event
    let scrollTimer;
    
    window.addEventListener('wheel', function(e) {
        clearTimeout(scrollTimer);
        
        // Chỉ phản ứng với các cử chỉ cuộn lớn hơn một ngưỡng nhất định
        const scrollDetectionThreshold = 20; // Tăng lên để giảm độ nhạy
        
        if (Math.abs(e.deltaY) < scrollDetectionThreshold) {
            // Bỏ qua các cử chỉ cuộn nhẹ
            return;
        }
        
        // Chỉ ẩn UI khi cuộn xuống, mức nhạy bình thường
        if (e.deltaY > scrollDetectionThreshold && window.scrollY > scrollThreshold) {
            forceHideUI();
        } 
        // Chỉ hiện UI khi cuộn mạnh lên trên - yêu cầu cuộn mạnh hơn nhiều
        else if (e.deltaY < -scrollDetectionThreshold * 3) {
            forceShowUI();
        }
        
        // Không tự hiện UI khi dừng cuộn ở giữa trang, chỉ hiện khi gần đầu trang
        scrollTimer = setTimeout(() => {
            if (window.scrollY < scrollThreshold / 2) { // Chỉ hiện khi rất gần đầu trang
                forceShowUI();
            }
        }, 500); // Tăng thời gian timeout để không hiện quá nhanh
    }, { passive: true });

    // Tương tự điều chỉnh cho touchmove
    window.addEventListener('touchmove', function(e) {
        const touchY = e.touches[0].clientY;
        const diff = touchStartY - touchY;
        
        clearTimeout(scrollTimer);
        
        // Chỉ phản ứng khi vuốt đủ mạnh
        const touchDetectionThreshold = 15; // Ngưỡng phát hiện vuốt (giá trị lớn hơn = ít nhạy hơn)
        
        if (Math.abs(diff) < touchDetectionThreshold) {
            // Bỏ qua các cử chỉ vuốt nhẹ
            return;
        }
        
        if (diff > touchDetectionThreshold && window.scrollY > scrollThreshold) {
            // Vuốt lên mạnh (cuộn xuống)
            forceHideUI();
        } else if (diff < -touchDetectionThreshold * 2) {
            // Vuốt xuống mạnh (cuộn lên)
            // Yêu cầu vuốt mạnh hơn để hiện UI
            forceShowUI();
        }
        
        touchStartY = touchY;
        
        scrollTimer = setTimeout(() => {
            if (window.scrollY < scrollThreshold) {
                forceShowUI();
            }
        }, 300); // Tăng thời gian timeout
    }, { passive: true });
</script>
@endsection 