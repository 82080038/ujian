$(document).ready(function() {
    // Auto-dismiss alert after 5s
    setTimeout(function() {
        $('.auto-dismiss').fadeOut('slow');
    }, 5000);

    // Disable right-click
    $(document).on('contextmenu', function(e) {
        if ($('body').hasClass('mode-ujian')) {
            e.preventDefault();
            return false;
        }
    });

    // Strict Anti-Cheat during exam
    if ($('body').hasClass('mode-ujian')) {
        let tabSwitchCount = parseInt(localStorage.getItem('tabSwitchCount') || '0');
        let cheatLog = [];

        // Tab visibility change
        $(document).on('visibilitychange', function() {
            if (document.hidden) {
                tabSwitchCount++;
                localStorage.setItem('tabSwitchCount', tabSwitchCount);
                cheatLog.push({type:'tab', time: new Date().toISOString()});
                if (tabSwitchCount >= 2) {
                    alert('PERINGATAN KERAS: Anda berpindah tab/tab window. Ujian akan disubmit.');
                    $('#form-ujian').submit();
                } else {
                    alert('PERINGATAN: Jangan berpindah tab/window saat ujian! Peringatan ke-' + tabSwitchCount);
                }
            }
        });

        // Window blur (alt-tab, minimize, etc)
        $(window).on('blur', function() {
            tabSwitchCount++;
            localStorage.setItem('tabSwitchCount', tabSwitchCount);
            cheatLog.push({type:'blur', time: new Date().toISOString()});
            if (tabSwitchCount >= 2) {
                alert('PERINGATAN KERAS: Window kehilangan fokus. Ujian disubmit otomatis.');
                $('#form-ujian').submit();
            }
        });

        // Prevent copy/paste/cut
        $(document).on('copy cut paste', function(e) {
            e.preventDefault();
            return false;
        });

        // Prevent F12, Ctrl+Shift+I, Ctrl+U
        $(document).on('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) || (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Opsi jawaban click
    $(document).on('click', '.opsi-jawaban', function() {
        const $this = $(this);
        const $container = $this.closest('.soal-box');
        $container.find('.opsi-jawaban').removeClass('terpilih');
        $this.addClass('terpilih');
        $this.find('input[type="radio"]').prop('checked', true);

        // Update navigasi warna via AJAX
        const soalId = $this.data('soal-id');
        const opsiId = $this.data('opsi-id');
        const paketId = $this.data('paket-id');
        const isRagu = $('#ragu-' + soalId).is(':checked') ? 1 : 0;

        $.ajax({
            url: '../api/simpan_jawaban_temp.php',
            method: 'POST',
            data: { soal_id: soalId, opsi_id: opsiId, paket_id: paketId, is_ragu: isRagu },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'ok') {
                    updateNavigasiColor(soalId, isRagu ? 'ragu' : 'dijawab');
                }
            }
        });
    });

    // Toggle ragu-ragu
    $(document).on('change', '.toggle-ragu', function() {
        const soalId = $(this).data('soal-id');
        const isRagu = $(this).is(':checked') ? 1 : 0;
        updateNavigasiColor(soalId, isRagu ? 'ragu' : 'dijawab');
    });
});

function updateNavigasiColor(soalId, status) {
    const $btn = $('#nav-btn-' + soalId);
    $btn.removeClass('belum dijawab ragu').addClass(status);
}

function startTimer(totalSeconds, displaySelector, formSelector) {
    // Try restore from localStorage
    let saved = localStorage.getItem('timer_remaining');
    let remaining = saved ? parseInt(saved) : totalSeconds;
    if (remaining <= 0 || remaining > totalSeconds) remaining = totalSeconds;

    const display = $(displaySelector);
    const form = $(formSelector);

    const interval = setInterval(function() {
        let m = Math.floor(remaining / 60);
        let s = remaining % 60;
        display.text((m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s));

        if (remaining <= 300) {
            display.addClass('timer-merah');
            showToast('Waktu tersisa ' + m + ' menit!');
        }

        if (remaining <= 0) {
            clearInterval(interval);
            localStorage.removeItem('timer_remaining');
            alert('Waktu habis! Ujian akan disubmit otomatis.');
            if (form) form.submit();
        }

        remaining--;
        localStorage.setItem('timer_remaining', remaining);
    }, 1000);

    return interval;
}

// Toast notification
function showToast(message, type) {
    const toastEl = document.getElementById('liveToast');
    if (!toastEl) return;
    const msgEl = document.getElementById('toastMessage');
    if (msgEl) msgEl.textContent = message;
    const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
    toast.show();
}
