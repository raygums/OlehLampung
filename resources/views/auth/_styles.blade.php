<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    /* ===== Page Wrapper ===== */
    .auth-wrap {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Inter', sans-serif;
        position: relative;
        overflow: hidden;
        padding: 2rem 1rem;
    }

    /* ===== Full-screen Background ===== */
    .auth-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
    }

    .auth-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.35) saturate(1.2);
    }

    .auth-bg-overlay {
        position: fixed;
        inset: 0;
        z-index: 1;
        background:
            radial-gradient(ellipse at 30% 0%, rgba(245, 158, 11, 0.15) 0%, transparent 60%),
            radial-gradient(ellipse at 70% 100%, rgba(245, 158, 11, 0.1) 0%, transparent 50%),
            linear-gradient(180deg, rgba(15, 17, 23, 0.3) 0%, rgba(15, 17, 23, 0.1) 50%, rgba(15, 17, 23, 0.5) 100%);
    }

    /* ===== Floating Particles ===== */
    .auth-particle {
        position: fixed;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.15);
        z-index: 1;
        pointer-events: none;
    }

    .auth-particle:nth-child(1) {
        width: 350px;
        height: 350px;
        top: -80px;
        left: -60px;
        filter: blur(100px);
        animation: drift 18s ease-in-out infinite;
    }

    .auth-particle:nth-child(2) {
        width: 250px;
        height: 250px;
        bottom: -40px;
        right: -40px;
        filter: blur(90px);
        animation: drift 22s ease-in-out infinite reverse;
    }

    .auth-particle:nth-child(3) {
        width: 120px;
        height: 120px;
        top: 40%;
        right: 15%;
        filter: blur(60px);
        opacity: 0.5;
        animation: drift 15s ease-in-out infinite 3s;
    }

    /* ===== Card Container ===== */
    .auth-container {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 440px;
        animation: cardEnter 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* ===== Logo ===== */
    .auth-logo {
        text-align: center;
        margin-bottom: 1.75rem;
    }

    .auth-logo a {
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #ffffff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .auth-logo a:hover {
        transform: scale(1.03);
    }

    .auth-logo .l-amber { color: #F59E0B; }
    .auth-logo .l-dot   { color: #F59E0B; font-size: 0.875rem; }

    .auth-logo p {
        color: rgba(255, 255, 255, 0.65);
        font-size: 0.875rem;
        margin-top: 0.375rem;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    /* ===== Glass Card ===== */
    .auth-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(24px) saturate(1.5);
        -webkit-backdrop-filter: blur(24px) saturate(1.5);
        border-radius: 1.5rem;
        padding: 2.25rem;
        box-shadow:
            0 20px 60px rgba(0, 0, 0, 0.2),
            0 1px 3px rgba(0, 0, 0, 0.06),
            inset 0 1px 0 rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .auth-heading {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1B1F2A;
        margin-bottom: 0.25rem;
    }

    .auth-sub {
        color: #9CA3AF;
        font-size: 0.8125rem;
        margin-bottom: 1.75rem;
    }

    /* ===== Error Alert ===== */
    .auth-error {
        background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
        border: 1px solid #FECACA;
        color: #DC2626;
        font-size: 0.8125rem;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: shake 0.4s ease;
    }

    .auth-error svg { flex-shrink: 0; }

    /* ===== Form Fields ===== */
    .auth-field {
        margin-bottom: 1.125rem;
    }

    .auth-field:last-of-type {
        margin-bottom: 1.625rem;
    }

    .auth-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .auth-label-hint {
        font-weight: 400;
        color: #9CA3AF;
    }

    .auth-input-wrap {
        position: relative;
        transition: transform 0.2s ease;
    }

    .auth-input-wrap:focus-within {
        transform: translateY(-1px);
    }

    .auth-input-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: #B0ADA5;
        pointer-events: none;
        transition: color 0.3s ease;
    }

    .auth-input {
        display: block;
        width: 100%;
        padding: 0.75rem 0.875rem 0.75rem 2.625rem;
        background: rgba(250, 248, 244, 0.7);
        border: 1.5px solid #E5E2D9;
        border-radius: 0.75rem;
        color: #1B1F2A;
        font-size: 0.875rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        outline: none;
    }

    .auth-input::placeholder {
        color: #C4C0B6;
    }

    .auth-input:focus {
        border-color: #F59E0B;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.12), 0 2px 8px rgba(245, 158, 11, 0.08);
    }

    .auth-input:focus ~ .auth-input-icon {
        color: #F59E0B;
    }

    /* ===== Toggle Password ===== */
    .auth-toggle-pw {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #B0ADA5;
        cursor: pointer;
        padding: 0.25rem;
        transition: all 0.2s;
        border-radius: 0.375rem;
    }

    .auth-toggle-pw:hover {
        color: #6B7280;
        background: rgba(0, 0, 0, 0.04);
    }

    /* ===== Submit Button ===== */
    .auth-submit {
        width: 100%;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.9375rem;
        font-weight: 700;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        letter-spacing: 0.01em;
        position: relative;
        overflow: hidden;
    }

    .auth-submit::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.15) 50%, transparent 60%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .auth-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .auth-submit:hover::after {
        transform: translateX(100%);
    }

    .auth-submit:active {
        transform: translateY(0);
    }

    /* ===== Divider ===== */
    .auth-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 1.25rem 0;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, #E5E2D9, transparent);
    }

    .auth-divider span {
        font-size: 0.6875rem;
        color: #B0ADA5;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 500;
    }

    /* ===== Footer Link ===== */
    .auth-footer {
        text-align: center;
        font-size: 0.8125rem;
        color: #6B7280;
    }

    .auth-footer a {
        color: #D97706;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border-bottom: 1.5px solid transparent;
    }

    .auth-footer a:hover {
        color: #B45309;
        border-bottom-color: #D97706;
    }

    /* ===== Back Link ===== */
    .auth-back {
        text-align: center;
        margin-top: 1.25rem;
        animation: cardEnter 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards;
        opacity: 0;
    }

    .auth-back a {
        font-size: 0.8125rem;
        color: rgba(255, 255, 255, 0.55);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s;
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .auth-back a:hover {
        color: #F59E0B;
    }

    /* ===== Bottom Branding ===== */
    .auth-branding {
        position: fixed;
        bottom: 1.5rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.4);
    }

    .auth-branding-dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
    }

    /* ===== Animations ===== */
    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(30px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes drift {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25%      { transform: translate(30px, -20px) scale(1.05); }
        50%      { transform: translate(-15px, 25px) scale(0.95); }
        75%      { transform: translate(20px, 10px) scale(1.02); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%      { transform: translateX(-6px); }
        40%      { transform: translateX(6px); }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(4px); }
    }
</style>
