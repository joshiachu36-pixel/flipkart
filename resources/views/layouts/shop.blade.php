<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Shop premium products at the best prices — Flipkart Marketplace">
    <title>Flipkart — Premium Marketplace</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ════════════════════════════════════════
           DESIGN SYSTEM — CSS CUSTOM PROPERTIES
           Premium color palette, typography & shadows
        ════════════════════════════════════════ */
        :root {
            --primary:          #2874f0;
            --primary-dark:     #1254c4;
            --primary-light:    #f0f6ff;
            --primary-rgb:      40, 116, 240;
            --accent:           #ff5a5a;
            --accent-orange:    #ff9f00;
            --success:          #00b074;
            --success-light:    #ebfaf4;
            --text-primary:     #1e293b;
            --text-secondary:   #475569;
            --text-muted:       #94a3b8;
            --bg-base:          #f8fafc;
            --bg-card:          #ffffff;
            --bg-sidebar:       #ffffff;
            --border-subtle:    #f1f5f9;
            --border-card:      #e2e8f0;
            --radius-sm:        8px;
            --radius-md:        12px;
            --radius-lg:        16px;
            --radius-xl:        24px;
            --shadow-sm:        0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.02);
            --shadow-md:        0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            --shadow-lg:        0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.02);
            --shadow-xl:        0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.04);
            --shadow-card-hover: 0 12px 30px rgba(40,116,240,0.08), 0 6px 12px rgba(0,0,0,0.03);
            --transition-fast:   0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-med:    0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow:   0.5s cubic-bezier(0.4, 0, 0.2, 1);
            --font-base:        'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* ════════════════════════════════════════
           GLOBAL RESET & BASE STYLES
        ════════════════════════════════════════ */
        body {
            font-family: var(--font-base);
            background-color: var(--bg-base);
            color: var(--text-primary);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* ════════════════════════════════════════
           UTILITY CLASSES
        ════════════════════════════════════════ */
        .text-primary-brand { color: var(--primary) !important; }
        .bg-primary-brand { background-color: var(--primary) !important; }
        .fw-800 { font-weight: 800; }
        .fw-700 { font-weight: 700; }
        .fw-600 { font-weight: 600; }
        .fs-xs { font-size: 0.75rem; }
        .fs-sm { font-size: 0.85rem; }

        /* ════════════════════════════════════════
           COMPONENTS
        ════════════════════════════════════════ */
        .card {
            border: 1px solid var(--border-card);
            border-radius: var(--radius-md);
            background: var(--bg-card);
            box-shadow: var(--shadow-sm);
        }

        /* Categories Tree custom styling */
        .cat-root { font-weight: 700; color: var(--text-primary); font-size: 0.95rem; }
        .cat-child { color: var(--text-secondary); padding-left: 8px; font-size: 0.88rem; }
        .cat-grandchild { color: var(--text-muted); padding-left: 16px; font-size: 0.82rem; }
        .toggle-icon {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-decoration: none;
            transition: color var(--transition-fast);
            border: none;
            background: none;
        }
        .toggle-icon:hover { color: var(--primary); }

        /* ════════════════════════════════════════
           PRODUCT CARDS (GRID VIEW & LIST VIEW)
        ════════════════════════════════════════ */
        .product-card-container {
            transition: all var(--transition-med);
        }

        .product-card {
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-card);
            background: var(--bg-card);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: transform var(--transition-med), box-shadow var(--transition-med), border-color var(--transition-med);
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-card-hover);
            border-color: rgba(var(--primary-rgb), 0.25);
        }

        /* Grid specific card image container */
        .product-img-wrap {
            position: relative;
            overflow: hidden;
            background: #f8fafc;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.06);
        }

        /* Hover overlay actions */
        .product-img-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            opacity: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: opacity var(--transition-fast);
            z-index: 5;
        }

        .product-card:hover .product-img-overlay {
            opacity: 1;
        }

        .overlay-btn {
            background: #ffffff;
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--text-primary);
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: background var(--transition-fast), color var(--transition-fast), transform var(--transition-fast);
            text-decoration: none;
        }

        .overlay-btn:hover {
            background: var(--primary);
            color: #ffffff;
            transform: scale(1.1);
        }

        .overlay-btn.wishlist-active {
            color: var(--accent);
        }
        .overlay-btn.wishlist-active:hover {
            color: #ffffff;
        }

        /* Badges */
        .badge-discount {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.3px;
            z-index: 3;
            box-shadow: 0 4px 10px rgba(16,185,129,0.25);
        }

        .badge-new {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            z-index: 3;
            box-shadow: 0 4px 10px rgba(99,102,241,0.25);
        }

        .badge-seller {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            color: var(--text-secondary);
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 30px;
            border: 1px solid var(--border-card);
            max-width: 100%;
            transition: border-color var(--transition-fast), color var(--transition-fast);
        }
        .badge-seller:hover {
            border-color: rgba(var(--primary-rgb), 0.5);
            color: var(--primary);
        }

        /* Card Contents */
        .product-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 8px;
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        .product-name:hover {
            color: var(--primary);
        }

        /* Ratings */
        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f8fafc;
            border-radius: 4px;
            padding: 2px 6px;
            border: 1px solid var(--border-card);
        }
        .rating-stars {
            color: #eab308;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .rating-count {
            color: var(--text-muted);
            font-size: 0.78rem;
            font-weight: 500;
        }

        /* Price Structure */
        .price-block {
            margin: 12px 0;
        }
        .price-current {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.4px;
        }
        .price-original {
            font-size: 0.88rem;
            color: var(--text-muted);
            text-decoration: line-through;
            margin-left: 8px;
        }
        .price-discount-text {
            font-size: 0.78rem;
            font-weight: 700;
            color: #10b981;
            margin-left: 8px;
        }

        /* Stock */
        .stock-in {
            color: var(--success);
            font-size: 0.76rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .stock-out {
            color: var(--accent);
            font-size: 0.76rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Card Action Buttons */
        .btn-cart {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 700;
            padding: 10px 0;
            width: 100%;
            transition: transform var(--transition-fast), box-shadow var(--transition-fast);
            letter-spacing: 0.2px;
            box-shadow: 0 4px 10px rgba(40,116,240,0.15);
        }
        .btn-cart:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(40,116,240,0.25);
            color: #ffffff;
        }
        .btn-cart:active {
            transform: translateY(0);
        }

        .btn-view {
            color: var(--text-secondary);
            border: 1px solid var(--border-card);
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 8px 0;
            width: 100%;
            background: #ffffff;
            transition: all var(--transition-fast);
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-view:hover {
            background: #f8fafc;
            color: var(--text-primary);
            border-color: var(--text-secondary);
        }

        /* ════════════════════════════════════════
           LIST VIEW CARD SYSTEM
        ════════════════════════════════════════ */
        .list-view-active .product-card-container {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }

        .list-view-active .product-card {
            flex-direction: row;
            height: auto;
            max-height: 240px;
        }

        .list-view-active .product-img-wrap {
            width: 240px;
            min-width: 240px;
            height: 240px;
            aspect-ratio: auto;
            border-right: 1px solid var(--border-card);
        }

        .list-view-active .product-card-body {
            padding: 24px;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
        }

        .list-view-active .card-meta-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .list-view-active .card-action-column {
            width: 200px;
            min-width: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            border-left: 1px dashed var(--border-card);
            padding-left: 20px;
        }

        /* ════════════════════════════════════════
           FILTER SIDEBAR STYLES
        ════════════════════════════════════════ */
        .shop-sidebar {
            position: sticky;
            top: 90px;
            z-index: 10;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            padding-right: 4px;
        }

        .sidebar-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 20px;
            margin-bottom: 20px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .sidebar-header-title {
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-section-title {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            user-select: none;
        }

        .filter-section-title i {
            transition: transform var(--transition-fast);
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .filter-section-title.collapsed i {
            transform: rotate(-90deg);
        }

        .filter-section-content {
            padding: 8px 0 16px 0;
        }

        /* Custom Checkbox Design */
        .custom-control {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            position: relative;
            cursor: pointer;
            user-select: none;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: color var(--transition-fast);
        }

        .custom-control:hover {
            color: var(--text-primary);
        }

        .custom-control-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .custom-control-label {
            position: relative;
            padding-left: 28px;
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: space-between;
        }

        .custom-control-indicator {
            position: absolute;
            top: 2px;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: #f1f5f9;
            border: 1px solid var(--border-card);
            border-radius: 4px;
            transition: all var(--transition-fast);
        }

        .custom-control:hover .custom-control-input ~ .custom-control-label .custom-control-indicator {
            background-color: #e2e8f0;
            border-color: #cbd5e1;
        }

        .custom-control-input:checked ~ .custom-control-label .custom-control-indicator {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .custom-control-indicator::after {
            content: "";
            position: absolute;
            display: none;
            left: 5px;
            top: 2px;
            width: 6px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .custom-control-input:checked ~ .custom-control-label .custom-control-indicator::after {
            display: block;
        }

        .filter-count-badge {
            font-size: 0.72rem;
            background: #f1f5f9;
            color: var(--text-muted);
            border-radius: 12px;
            padding: 1px 6px;
            font-weight: 600;
        }

        /* Color Swatches Filters */
        .color-swatch-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .color-swatch-item {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid var(--border-card);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform var(--transition-fast), border-color var(--transition-fast);
        }
        .color-swatch-item:hover {
            transform: scale(1.1);
        }
        .color-swatch-item.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(40,116,240,0.2);
        }
        .color-swatch-item.active::after {
            content: "\F272";
            font-family: "bootstrap-icons";
            font-size: 0.75rem;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        }
        .color-swatch-item.light-color.active::after {
            color: #1e293b;
            text-shadow: none;
        }

        /* Size Pill Filters */
        .size-pill-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .size-pill-item {
            padding: 6px 12px;
            border: 1px solid var(--border-card);
            background: #ffffff;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            text-align: center;
            min-width: 40px;
            transition: all var(--transition-fast);
            user-select: none;
        }
        .size-pill-item:hover {
            border-color: #94a3b8;
            color: var(--text-primary);
        }
        .size-pill-item.active {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Rating Stars Filter Row */
        .rating-filter-row {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            transition: background var(--transition-fast);
        }
        .rating-filter-row:hover {
            background: #f1f5f9;
        }
        .rating-filter-row.active {
            background: var(--primary-light);
        }

        /* ════════════════════════════════════════
           SORT / TOOLBAR BAR
        ════════════════════════════════════════ */
        .sort-toolbar {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .toolbar-views {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .view-toggle-btn {
            background: #ffffff;
            border: 1px solid var(--border-card);
            border-radius: 6px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        .view-toggle-btn:hover {
            border-color: #cbd5e1;
            color: var(--text-primary);
        }
        .view-toggle-btn.active {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Dropdown custom look */
        .custom-select-wrap {
            position: relative;
        }
        .custom-select {
            appearance: none;
            background-color: #ffffff;
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 8px 36px 8px 16px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            outline: none;
            transition: all var(--transition-fast);
        }
        .custom-select:hover {
            border-color: #cbd5e1;
            color: var(--text-primary);
        }
        .custom-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(40,116,240,0.15);
        }
        .custom-select-wrap::after {
            content: "\F2E2";
            font-family: "bootstrap-icons";
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ════════════════════════════════════════
           SHOP HERO SECTION
        ════════════════════════════════════════ */
        .shop-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #1e293b 100%);
            padding: 40px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .shop-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(40, 116, 240, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .shop-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .shop-hero-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.75px;
            margin-bottom: 8px;
        }
        .shop-hero-breadcrumb .breadcrumb-item,
        .shop-hero-breadcrumb .breadcrumb-item a {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        .shop-hero-breadcrumb .breadcrumb-item a:hover {
            color: #ffffff;
        }
        .shop-hero-breadcrumb .breadcrumb-item.active { color: #ffffff; font-weight: 600; }
        .shop-hero-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255,255,255,0.3);
        }
        .shop-hero-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 50px;
            backdrop-filter: blur(8px);
            margin-top: 14px;
        }
        .shop-hero-desc {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            margin-top: 8px;
            max-width: 600px;
            line-height: 1.5;
        }

        /* ════════════════════════════════════════
           BRAND SCROLL
        ════════════════════════════════════════ */
        .brand-scroll-wrap {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            padding-bottom: 12px;
        }
        .brand-card {
            flex-shrink: 0;
            width: 140px;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--border-card);
            transition: all var(--transition-fast);
        }
        .brand-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            color: inherit;
            text-decoration: none;
        }
        .brand-logo-wrap {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            border: 1px solid var(--border-card);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .brand-logo-placeholder {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #ffffff;
        }
        .brand-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .brand-visit-label {
            font-size: 0.72rem;
            color: var(--primary);
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        /* ════════════════════════════════════════
           ACTIVE FILTERS TAG BAR
        ════════════════════════════════════════ */
        .active-filters-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
            animation: fadeIn 0.3s ease;
        }
        .active-filters-label {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-right: 4px;
        }
        .filter-chip {
            background: #ffffff;
            border: 1px solid var(--border-card);
            color: var(--text-secondary);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all var(--transition-fast);
        }
        .filter-chip:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #fff5f5;
        }

        /* ════════════════════════════════════════
           SKELETON CARDS LOADING
        ════════════════════════════════════════ */
        .skeleton-card {
            background: #ffffff;
            border: 1px solid var(--border-card);
            border-radius: var(--radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .skeleton-img-placeholder {
            aspect-ratio: 1/1;
            background: #e2e8f0;
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        .skeleton-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .skeleton-bar {
            height: 14px;
            background: #e2e8f0;
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
        }
        .skeleton-bar.title { width: 90%; height: 18px; }
        .skeleton-bar.text-short { width: 50%; }
        .skeleton-bar.price { width: 40%; height: 22px; margin-top: 6px; }
        .skeleton-bar.button { width: 100%; height: 38px; margin-top: 8px; border-radius: var(--radius-sm); }

        /* ════════════════════════════════════════
           RESPONSIVE STYLE UPDATES
        ════════════════════════════════════════ */
        @media (max-width: 991px) {
            .shop-sidebar {
                position: fixed;
                top: 0;
                left: -320px;
                width: 300px;
                height: 100vh;
                max-height: 100vh;
                background: #ffffff;
                box-shadow: var(--shadow-xl);
                z-index: 1100;
                transition: left var(--transition-med);
                padding: 24px;
            }
            .shop-sidebar.open {
                left: 0;
            }
            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.4);
                backdrop-filter: blur(4px);
                z-index: 1090;
                opacity: 0;
                pointer-events: none;
                transition: opacity var(--transition-med);
            }
            .sidebar-overlay.open {
                opacity: 1;
                pointer-events: auto;
            }
        }
        @media (max-width: 768px) {
            .shop-hero-title { font-size: 1.75rem; }
            .sort-toolbar { flex-direction: column; align-items: stretch; gap: 12px; }
            .sort-toolbar > div { justify-content: space-between; width: 100%; }
            .list-view-active .product-card { flex-direction: column; max-height: none; }
            .list-view-active .product-img-wrap { width: 100%; height: auto; aspect-ratio: 1/1; border-right: none; border-bottom: 1px solid var(--border-card); }
            .list-view-active .product-card-body { flex-direction: column; align-items: stretch; }
            .list-view-active .card-action-column { width: 100%; border-left: none; border-top: 1px dashed var(--border-card); padding-left: 0; padding-top: 16px; margin-top: 10px; }
        }
    </style>


</head>

<body>

    @include('layout.shop-header')

    @yield('content')

    @include('layout.shop-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>