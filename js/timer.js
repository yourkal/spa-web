/**
 * Realtime Timer Engine for Spa Sessions
 * Handles countdown, progress bars, audio alert triggers, warning states, and UI updates
 */

class SessionTimerEngine {
  constructor() {
    this.intervalId = null;
    this.audioAlertPlayed = {};
    this.init();
  }

  init() {
    this.startGlobalTimer();
  }

  startGlobalTimer() {
    if (this.intervalId) clearInterval(this.intervalId);

    this.intervalId = setInterval(() => {
      this.tick();
    }, 1000);
  }

  tick() {
    const sessions = window.spaStore.getActiveSessions();
    let updated = false;
    const now = Date.now();

    sessions.forEach(session => {
      if (session.status === 'running' && !session.isPaused) {
        if (!session.targetEndTime) {
          session.targetEndTime = now + ((session.remainingSeconds || 0) * 1000);
        }
        const calcRemaining = Math.max(0, Math.round((session.targetEndTime - now) / 1000));
        const prevRemaining = session.remainingSeconds;
        session.remainingSeconds = calcRemaining;
        updated = true;

        // Warning trigger if 10 minutes (600 seconds) remaining
        if (prevRemaining > 600 && calcRemaining <= 600 && !this.audioAlertPlayed[session.id + '_10m']) {
          this.audioAlertPlayed[session.id + '_10m'] = true;
          if (window.showToast) {
            window.showToast(`⚠️ Perhatian: Waktu spa ${session.customerName} tersisa 10 menit!`, 'warning');
          }
        }

        // Trigger finished when 0
        if (calcRemaining === 0 && session.status !== 'finished') {
          session.status = 'finished';
          if (window.showToast) {
            window.showToast(`🔴 WAKTU SPA SELESAI: Session ${session.customerName} (${session.serviceName}) telah habis!`, 'error');
          }
          window.spaStore.addNotification('🔴 Waktu Spa Selesai', `Waktu perawatan ${session.customerName} telah berakhir.`);
        }
      }
    });

    if (updated) {
      if (window.spaStore) window.spaStore.save();
      this.updateTimerUIElements();
    }
  }

  formatTime(seconds) {
    if (seconds <= 0) return '00:00';
    const hrs = Math.floor(seconds / 3600);
    const mins = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;

    if (hrs > 0) {
      return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
  }

  updateTimerUIElements() {
    const sessions = window.spaStore.getActiveSessions();

    sessions.forEach(session => {
      // Update DOM timer text elements
      const timerTexts = document.querySelectorAll(`[data-timer-id="${session.id}"]`);
      timerTexts.forEach(el => {
        el.textContent = this.formatTime(session.remainingSeconds);
        
        // Visual alert styling for remaining < 10 mins (600s)
        if (session.remainingSeconds <= 600 && session.remainingSeconds > 0) {
          el.classList.add('text-amber-600', 'font-extrabold', 'animate-pulse');
        } else if (session.remainingSeconds === 0) {
          el.classList.remove('text-amber-600', 'animate-pulse');
          el.classList.add('text-rose-600', 'font-extrabold');
        }
      });

      // Update progress bars
      const progressBars = document.querySelectorAll(`[data-progress-id="${session.id}"]`);
      progressBars.forEach(bar => {
        const totalSecs = session.totalDurationMinutes * 60;
        const elapsedSecs = totalSecs - session.remainingSeconds;
        const percentage = Math.min(100, Math.max(0, (elapsedSecs / totalSecs) * 100));
        bar.style.width = `${percentage}%`;

        if (session.remainingSeconds <= 600) {
          bar.classList.remove('bg-emerald-500');
          bar.classList.add('bg-amber-500');
        }
      });
    });
  }
}

// Instantiate global timer
window.spaTimer = new SessionTimerEngine();
