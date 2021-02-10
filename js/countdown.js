(function ($) {
    "use strict";

    const deadline = new Date("Feb 16, 2021 11:00:00").getTime();
    const x = setInterval(() => {
        const now = new Date().getTime();
        const t = deadline - now;
        const days = Math.floor(t / (1000 * 60 * 60 * 24));
        const hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
        const minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((t % (1000 * 60)) / 1000);

        const _days = $('.days');
        const _hours = $('.hours');
        const _minutes = $('.minutes');

        _days.text(days.toString().length === 1 ? `0${days}` : days);
        _hours.text(hours.toString().length === 1 ? `0${hours}` : hours);
        _minutes.text(minutes.toString().length === 1 ? `0${minutes}` : minutes);

        if (t < 0) {
            clearInterval(x);
            _days.text('00');
            _hours.text('00');
            _minutes.text('00');
        }
    }, 1000);
}(jQuery));
