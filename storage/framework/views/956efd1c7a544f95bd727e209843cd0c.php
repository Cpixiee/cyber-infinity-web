<?php $__env->startSection('title', 'Home - Cyber Infinity'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');

    .hero-section {
        position: relative;
        min-height: 100vh;
        background: #000;
        overflow: hidden;
        font-family: 'Share Tech Mono', monospace;
        display: flex;
        flex-direction: column;
    }
    
    /* Responsive breakpoints */
    @media (min-width: 1024px) {
        .hero-section {
            flex-direction: row;
        }
    }

    .video-background {
        position: relative;
        width: 100%;
        height: 40vh;
        overflow: hidden;
    }
    
    /* Desktop layout */
    @media (min-width: 1024px) {
        .video-background {
            width: 50%;
            height: auto;
        }
    }

    .video-background video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        transform: translate(-50%, -50%);
        object-fit: cover;
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            rgba(0, 0, 0, 0.3), 
            rgba(0, 0, 0, 0.5)
        );
    }

    .hero-content {
        position: relative;
        width: 100%;
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 2;
        min-height: 60vh;
    }
    
    /* Desktop layout */
    @media (min-width: 1024px) {
        .hero-content {
            width: 50%;
            padding: 0 4rem;
            min-height: auto;
        }
    }

    .cyber-title {
        font-size: 2.5rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 1.5rem;
        color: #fff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        letter-spacing: 1px;
        line-height: 1.2;
    }
    
    /* Desktop title */
    @media (min-width: 768px) {
        .cyber-title {
            font-size: 4rem;
            text-align: left;
        }
    }
    
    @media (min-width: 1024px) {
        .cyber-title {
            font-size: 5rem;
            letter-spacing: 2px;
            margin-bottom: 2rem;
        }
    }

    .cyber-subtitle {
        font-size: 1rem;
        text-align: center;
        color: #a0a0a0;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    /* Desktop subtitle */
    @media (min-width: 768px) {
        .cyber-subtitle {
            font-size: 1.1rem;
            text-align: left;
        }
    }
    
    @media (min-width: 1024px) {
        .cyber-subtitle {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            line-height: 1.8;
        }
    }

    .buttons-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 2rem;
        align-items: center;
    }
    
    /* Desktop buttons */
    @media (min-width: 768px) {
        .buttons-container {
            flex-direction: row;
            gap: 20px;
            align-items: flex-start;
        }
    }

    .cyber-button {
        padding: 16px 32px;
        font-size: 1rem;
        color: #fff;
        background: #2bd025;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .cyber-button:hover {
        background: #24b01f;
        transform: translateY(-2px);
    }

    .cyber-button.secondary {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .cyber-button.secondary:hover {
        background: rgba(255, 255, 255, 0.1);
    }



    @media (max-width: 1024px) {
        .buttons-container {
            justify-content: center;
        }

        .trial-button {
            justify-content: center;
        }
    }

    /* Services Section */
    .services-section {
        padding: 80px 0;
        background: #0a0a0a;
    }

    .services-title {
        text-align: center;
        margin-bottom: 60px;
    }

    .services-title h2 {
        font-size: 2.5rem;
        color: #00ff00;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
    }

    .services-title p {
        color: #ffffff;
        font-size: 1.2rem;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        padding: 0 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .service-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #00ff00;
        padding: 30px;
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 255, 0, 0.2);
    }

    .service-card h3 {
        color: #00ffff;
        font-size: 1.5rem;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .service-card p {
        color: #cccccc;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .cyber-title {
            font-size: 2.5rem;
        }

        .cyber-subtitle {
            font-size: 1.1rem;
            padding: 0 20px;
        }

        .buttons-container {
            flex-direction: column;
            align-items: center;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="hero-section">
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <source src="<?php echo e(asset('images/cyber-bg.mp4')); ?>" type="video/mp4">
        </video>
        <div class="video-overlay"></div>
    </div>
    <div class="hero-content">
        <h1 class="cyber-title">Welcome Cyber Infinity</h1>
        <p class="cyber-subtitle">
            this is the cyber security infinity website from SMKN 22 Jakarta, This website will provide Experiences about cyber security.
        </p>
        <div class="buttons-container">
            <a href="<?php echo e(route('register')); ?>" class="cyber-button">Read more</a>
            <a href="<?php echo e(route('login')); ?>" class="cyber-button secondary">Get started</a>
        </div>
    </div>
</div>

<div class="services-section">
    <div class="services-title">
        <h2>Learn</h2>
        <p>bootcamp and cyber training</p>
    </div>

    <div class="services-grid">
        <div class="service-card">
            <h3>Cyber Security Training</h3>
            <p>cyber security training at vocational high school students scale</p>
        </div>

        <div class="service-card">
            <h3>Internal Selection</h3>
            <p>selection for the vocational high school (SMK) national competition</p>
        </div>

        <div class="service-card">
            <h3>Security Bootcamp</h3>
            <p>providing a bootcamp to external vocational school students</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cyber-infinity-web\resources\views/home.blade.php ENDPATH**/ ?>