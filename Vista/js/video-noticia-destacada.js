document.querySelectorAll('#noticia-destacada').forEach(card => {
    const video = card.querySelector('.video-layer');

    card.addEventListener('mouseenter', () => {
        video.currentTime = 0;
        video.play();
    });

    card.addEventListener('mouseleave', () => {
        video.pause();
        video.currentTime = 0;
    });
});

const video = document.getElementById('video-incendio');
const btn = document.querySelector('.mute-btn');

btn.addEventListener('click', () => {
    video.muted = !video.muted;
    btn.textContent = video.muted ? '🔇' : '🔊';
    btn.setAttribute('aria-pressed', String(video.muted));
});