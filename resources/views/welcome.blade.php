<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Innova Corporativo</title>
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --bg-dark: #121212;
      --accent-orange: #FF6B3B;
      --accent-gold: #FFC145;
      --text-light: #F2F2F2;
      --text-muted: #B0B0B0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Work Sans', sans-serif;
      background: var(--bg-dark);
      color: var(--text-light);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
      position: relative;
    }

    .animated-gradient {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(-45deg, #121212, #1e1e1e, #121212, #1e1e1e);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      z-index: -1;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    header {
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(6px);
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--accent-orange);
    }

    .login-link {
      color: var(--text-muted);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .login-link:hover {
      color: var(--text-light);
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 4rem 2rem;
    }

    .hero {
      max-width: 640px;
      display: grid;
      gap: 2rem;
      animation: fadeInUp 1s ease-out forwards;
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(50px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .hero h1 {
      font-size: 2.8rem;
      color: var(--accent-orange);
    }

    .hero p {
      color: var(--text-muted);
      font-size: 1.2rem;
    }

    .btn-cta {
      background-color: var(--accent-orange);
      color: var(--bg-dark);
      border: none;
      padding: 0.9rem 2.2rem;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 8px;
      cursor: pointer;
      transition: transform 0.3s ease, background-color 0.3s ease;
      box-shadow: 0 0 12px rgba(255, 107, 59, 0.4);
    }

    .btn-cta:hover {
      background-color: var(--accent-gold);
      color: var(--bg-dark);
      transform: scale(1.05);
    }

    .footer {
      text-align: center;
      padding: 2rem 1rem;
      color: var(--text-muted);
      background: rgba(255, 255, 255, 0.03);
      border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.2rem;
      }

      .hero p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="animated-gradient"></div>

  <header>
    <div class="logo">Innova</div>
    <a href="{{ route('login') }}" class="login-link">Log in</a>
  </header>

  <main>
    <section class="hero">
      <h1>Innova Corporativo</h1>
       <p>Plataforma integral para optimizar, automatizar y escalar las operaciones en el sector minero.</p>
      <a href="{{ route('login') }}" class="btn-cta">Log in</a>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Innova Corporativo.</p>
  </footer>

</body>
</html>
