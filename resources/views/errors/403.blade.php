<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Access Denied | Admin Panel</title>
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Source Sans 3', sans-serif;
        }
        .error-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 3rem 3.5rem;
            text-align: center;
            max-width: 480px;
            width: 90%;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }
        .shield-icon {
            width: 90px;
            height: 90px;
            background: rgba(220, 53, 69, 0.15);
            border: 2px solid rgba(220, 53, 69, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.3); }
            50% { box-shadow: 0 0 0 16px rgba(220, 53, 69, 0); }
        }
        .error-code {
            font-size: 6rem;
            font-weight: 900;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        .error-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        .error-message {
            color: rgba(255,255,255,0.65);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        .permission-slug {
            display: inline-block;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 6px;
            color: #ffd43b;
            font-family: monospace;
            font-size: 0.82rem;
            padding: 0.2rem 0.6rem;
            margin-bottom: 1.5rem;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 1.5rem 0;
        }
        .btn-go-back {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            border-radius: 50px;
            padding: 0.55rem 1.8rem;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
            margin-right: 0.5rem;
        }
        .btn-go-back:hover {
            background: rgba(255,255,255,0.18);
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-dashboard {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border: none;
            color: #fff;
            border-radius: 50px;
            padding: 0.55rem 1.8rem;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }
        .btn-dashboard:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13,110,253,0.4);
        }
        .user-info {
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: rgba(255,255,255,0.55);
            font-size: 0.82rem;
        }
        .user-info strong {
            color: rgba(255,255,255,0.8);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="shield-icon">
            <i class="bi bi-shield-lock-fill text-danger" style="font-size: 2.5rem;"></i>
        </div>

        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>

        <p class="error-message">
            You do not have permission to access this page or perform this action.
        </p>

        @php
            $msg = $exception->getMessage() ?? '';
            // Extract permission slug from message if present
            preg_match('/Required: ([a-z_.]+)/', $msg, $matches);
            $requiredPermission = $matches[1] ?? null;
        @endphp

        @if($requiredPermission)
        <div class="permission-slug">
            <i class="bi bi-key me-1"></i>{{ $requiredPermission }}
        </div>
        @endif

        <div class="user-info mb-3">
            @if(auth()->guard('staff')->check())
                @php $staff = auth()->guard('staff')->user(); @endphp
                Logged in as <strong>{{ $staff->name }}</strong>
                @if($staff->role)
                    with role <strong>{{ $staff->role->name }}</strong>
                @endif
            @elseif(auth()->guard('web')->check())
                Logged in as <strong>{{ auth()->guard('web')->user()->name }}</strong>
            @else
                <span>Not authenticated</span>
            @endif
        </div>

        <hr class="divider">

        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
            <a href="javascript:history.back()" class="btn-go-back">
                <i class="bi bi-arrow-left"></i> Go Back
            </a>
            <a href="{{ url('/') }}" class="btn-dashboard">
                <i class="bi bi-house-fill"></i> Dashboard
            </a>
        </div>

        <p class="text-muted mt-3" style="font-size:0.75rem">
            If you believe this is a mistake, contact your Super Admin to update your role permissions.
        </p>
    </div>
</body>
</html>
