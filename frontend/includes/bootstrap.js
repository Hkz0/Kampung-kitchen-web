function loadBootstrap() {
    const head = document.head;

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css';
    head.appendChild(link);

    const script1 = document.createElement('script');
    script1.src = 'https://code.jquery.com/jquery-3.5.1.slim.min.js';
    head.appendChild(script1);

    const script2 = document.createElement('script');
    script2.src = 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js';
    head.appendChild(script2);

    const script3 = document.createElement('script');
    script3.src = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js';
    head.appendChild(script3);
}

document.addEventListener("DOMContentLoaded", loadBootstrap);
