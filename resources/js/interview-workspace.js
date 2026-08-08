/**
 * Dual classic / IDE workspace for /interview/start/...
 * Exposed as window.createInterviewWorkspace() so it works even if
 * alpine:init already fired before this Vite module loads.
 */
const LAYOUT_KEY = 'interviewWorkspaceLayout';
const PANELS_KEY = 'interviewIdePanels';
const DEFAULT_PANELS = { left: 26, right: 26, bottom: 22 };

function clamp(n, min, max) {
    return Math.min(max, Math.max(min, n));
}

window.InterviewWorkspace = {
    LAYOUT_KEY,
    PANELS_KEY,

    get() {
        try {
            const stored = localStorage.getItem(LAYOUT_KEY);
            if (stored === 'classic') return 'classic';
            return 'ide';
        } catch {
            return 'ide';
        }
    },

    set(layout) {
        const next = layout === 'ide' ? 'ide' : 'classic';
        try {
            localStorage.setItem(LAYOUT_KEY, next);
        } catch {
            /* ignore */
        }
        this.syncDocClass(next);
        window.dispatchEvent(
            new CustomEvent('workspace-layout-changed', { detail: { layout: next } }),
        );
    },

    syncDocClass(layout = this.get()) {
        const onWorkspace = !!document.querySelector('.interview-workspace');
        document.documentElement.classList.toggle(
            'interview-ide',
            onWorkspace && layout === 'ide',
        );
    },

    restoreDom() {
        const root = document.querySelector('.interview-workspace');
        if (!root) {
            document.documentElement.classList.remove('interview-ide');
            return;
        }

        const layout = this.get();
        const ide = layout === 'ide';
        root.classList.toggle('workspace-ide', ide);
        root.classList.toggle('workspace-classic', !ide);
        this.syncDocClass(layout);

        if (ide) {
            const panels = this.getPanels();
            root.style.setProperty('--ide-left', `${panels.left}%`);
            root.style.setProperty('--ide-right', `${panels.right}%`);
            root.style.setProperty('--ide-bottom', `${panels.bottom}%`);
        } else {
            root.style.removeProperty('--ide-left');
            root.style.removeProperty('--ide-right');
            root.style.removeProperty('--ide-bottom');
        }

        const body = root.querySelector('.workspace-body');
        if (body) {
            body.classList.toggle('workspace-body-classic', !ide);
            body.classList.add('relative');
        }

        root.querySelectorAll('.editor-mount').forEach((el) => {
            el.classList.add('flex-1', 'min-h-0', 'flex', 'flex-col', 'relative', 'overflow-hidden');
        });

        let mobileTab = 'code';
        try {
            if (root._x_dataStack?.[0]?.mobileTab) {
                mobileTab = root._x_dataStack[0].mobileTab;
            }
        } catch {
            /* ignore */
        }
        const panelMap = {
            problem: root.querySelector('.panel-problem'),
            code: root.querySelector('.panel-editor-col'),
            chat: root.querySelector('.panel-meta'),
        };
        Object.entries(panelMap).forEach(([key, el]) => {
            if (!el) return;
            el.classList.add('ide-panel');
            el.classList.toggle(
                'is-mobile-active',
                ide &&
                    ((key === 'problem' && mobileTab === 'problem') ||
                        (key === 'code' && mobileTab === 'code') ||
                        (key === 'chat' && mobileTab === 'chat')),
            );
        });

        window.dispatchEvent(new Event('resize'));
        const iframe = document.getElementById('codeIframe');
        if (iframe?.contentWindow) {
            try {
                iframe.contentWindow.postMessage({ layout: true }, '*');
            } catch {
                /* ignore */
            }
        }
    },

    getPanels() {
        try {
            const raw = JSON.parse(localStorage.getItem(PANELS_KEY) || 'null');
            if (!raw || typeof raw !== 'object') return { ...DEFAULT_PANELS };
            return {
                left: clamp(Number(raw.left) || DEFAULT_PANELS.left, 14, 45),
                right: clamp(Number(raw.right) || DEFAULT_PANELS.right, 14, 45),
                bottom: clamp(Number(raw.bottom) || DEFAULT_PANELS.bottom, 12, 50),
            };
        } catch {
            return { ...DEFAULT_PANELS };
        }
    },

    savePanels(panels) {
        try {
            localStorage.setItem(PANELS_KEY, JSON.stringify(panels));
        } catch {
            /* ignore */
        }
    },
};

/**
 * @param {{ isChallengeSolved?: boolean, stats?: object, hasNextChallenge?: boolean }} [initial]
 */
window.createInterviewWorkspace = function createInterviewWorkspace(initial = {}) {
    const panels = window.InterviewWorkspace.getPanels();
    const stats = {
        total_user_bonus_xp: 0,
        total_user_extra_xp: 0,
        solved_challenges_count: 0,
        total_challenges_count: 0,
        attempts: 0,
        total_bonus: 0,
        total_user_bonus: 0,
        ...(initial.stats || {}),
    };

    return {
        layout: window.InterviewWorkspace.get(),
        mobileTab: 'code',
        sessionStatsOpen: false,
        timerDisplay: '--:--:--',
        left: panels.left,
        right: panels.right,
        bottom: panels.bottom,
        isChallengeSolved: !!initial.isChallengeSolved,
        hasNextChallenge: !!initial.hasNextChallenge,
        stats,
        dragging: null,
        _pointerId: null,
        _captureEl: null,
        _onMove: null,
        _onUp: null,
        _onExternal: null,
        _onSessionStats: null,
        _onTimerTick: null,

        init() {
            this.layout = window.InterviewWorkspace.get();
            const p = window.InterviewWorkspace.getPanels();
            this.left = p.left;
            this.right = p.right;
            this.bottom = p.bottom;
            window.InterviewWorkspace.syncDocClass(this.layout);

            this._onExternal = (e) => {
                if (e.detail?.layout && e.detail.layout !== this.layout) {
                    this.layout = e.detail.layout;
                    this.ensureEditorVisible();
                    this.$nextTick(() => this.notifyResize());
                }
            };
            window.addEventListener('workspace-layout-changed', this._onExternal);

            // Parent Start skipRender() on solve — stats arrive via browser event only
            this._onSessionStats = (e) => {
                const payload = Array.isArray(e.detail) ? e.detail[0] : e.detail;
                if (!payload || typeof payload !== 'object') return;
                if (payload.is_challenge_solved != null) {
                    this.isChallengeSolved = !!payload.is_challenge_solved;
                }
                if (payload.has_next_challenge != null) {
                    this.hasNextChallenge = !!payload.has_next_challenge;
                }
                [
                    'total_user_bonus_xp',
                    'total_user_extra_xp',
                    'solved_challenges_count',
                    'total_challenges_count',
                    'attempts',
                    'total_bonus',
                    'total_user_bonus',
                ].forEach((key) => {
                    if (payload[key] != null) this.stats[key] = payload[key];
                });
            };
            window.addEventListener('session-stats-updated', this._onSessionStats);

            this._onTimerTick = (e) => {
                const tick = e.detail || {};
                if (tick.hours == null) return;
                this.timerDisplay = `${tick.hours}:${tick.minutes}:${tick.seconds}`;
            };
            window.addEventListener('interview-timer-tick', this._onTimerTick);

            this.$watch('layout', (value) => {
                this.ensureEditorVisible();
                try {
                    localStorage.setItem(LAYOUT_KEY, value === 'ide' ? 'ide' : 'classic');
                } catch {
                    /* ignore */
                }
                window.InterviewWorkspace.syncDocClass(value);
                window.dispatchEvent(
                    new CustomEvent('workspace-layout-changed', { detail: { layout: value } }),
                );
                this.$nextTick(() => this.notifyResize());
            });

            this.$watch('mobileTab', () => {
                this.$nextTick(() => this.notifyResize());
            });

            this.$watch('left', () => this.persistPanels());
            this.$watch('right', () => this.persistPanels());
            this.$watch('bottom', () => this.persistPanels());

            this.$nextTick(() => this.notifyResize());
        },

        destroy() {
            if (this._onExternal) {
                window.removeEventListener('workspace-layout-changed', this._onExternal);
            }
            if (this._onSessionStats) {
                window.removeEventListener('session-stats-updated', this._onSessionStats);
            }
            if (this._onTimerTick) {
                window.removeEventListener('interview-timer-tick', this._onTimerTick);
            }
            this.stopDrag();
            queueMicrotask(() => {
                if (!document.querySelector('.interview-workspace')) {
                    document.documentElement.classList.remove('interview-ide');
                }
            });
        },

        setMobileTab(tab) {
            const next = tab === 'problem' || tab === 'chat' ? tab : 'code';
            this.mobileTab = next;
            this.$nextTick(() => this.notifyResize());
        },

        ensureEditorVisible() {
            if (this.layout === 'ide' && this.$refs.classicEditorBody) {
                this.$refs.classicEditorBody.classList.remove('hidden');
            }
        },

        setLayout(mode) {
            this.layout = mode === 'ide' ? 'ide' : 'classic';
        },

        toggleLayout() {
            this.setLayout(this.layout === 'ide' ? 'classic' : 'ide');
        },

        persistPanels() {
            window.InterviewWorkspace.savePanels({
                left: this.left,
                right: this.right,
                bottom: this.bottom,
            });
        },

        panelStyle() {
            if (this.layout !== 'ide') return '';
            return `--ide-left:${this.left}%;--ide-right:${this.right}%;--ide-bottom:${this.bottom}%;`;
        },

        notifyResize() {
            window.dispatchEvent(new Event('resize'));
            const iframe = document.getElementById('codeIframe');
            if (iframe?.contentWindow) {
                try {
                    iframe.contentWindow.postMessage({ layout: true }, '*');
                } catch {
                    /* ignore */
                }
            }
        },

        startDrag(axis, event) {
            if (this.layout !== 'ide') return;
            // Already dragging — ignore secondary pointers / double starts
            if (this.dragging) return;
            event.preventDefault();
            event.stopPropagation();

            this.dragging = axis;
            this._pointerId = event.pointerId;
            this._captureEl = event.currentTarget || event.target;

            // Keep events on the splitter even when the cursor crosses iframes (Monaco / output).
            try {
                this._captureEl?.setPointerCapture?.(event.pointerId);
            } catch {
                /* ignore — older browsers / already captured */
            }

            this._onMove = (e) => {
                if (this._pointerId != null && e.pointerId !== this._pointerId) return;
                this.onDrag(e);
            };
            this._onUp = (e) => {
                // blur has no pointerId — still end the drag
                if (
                    e &&
                    e.type !== 'blur' &&
                    this._pointerId != null &&
                    e.pointerId !== this._pointerId
                ) {
                    return;
                }
                this.stopDrag();
            };

            // Capture phase on document so release is seen even if something stops bubbling.
            document.addEventListener('pointermove', this._onMove, true);
            document.addEventListener('pointerup', this._onUp, true);
            document.addEventListener('pointercancel', this._onUp, true);
            document.addEventListener('lostpointercapture', this._onUp, true);
            window.addEventListener('blur', this._onUp);

            document.body.classList.add('ide-dragging');
            if (axis === 'bottom') document.body.classList.add('ide-dragging-row');
        },

        onDrag(event) {
            const root = this.$refs.workspaceBody;
            if (!root || !this.dragging) return;
            const rect = root.getBoundingClientRect();

            if (this.dragging === 'left') {
                this.left = clamp(((event.clientX - rect.left) / rect.width) * 100, 14, 45);
            } else if (this.dragging === 'right') {
                this.right = clamp(((rect.right - event.clientX) / rect.width) * 100, 14, 45);
            } else if (this.dragging === 'bottom') {
                const editorCol = root.querySelector('.panel-editor-col');
                if (!editorCol) return;
                const er = editorCol.getBoundingClientRect();
                this.bottom = clamp(((er.bottom - event.clientY) / er.height) * 100, 12, 50);
            }
        },

        stopDrag() {
            if (!this.dragging && !this._onMove && !this._onUp) return;

            const onMove = this._onMove;
            const onUp = this._onUp;
            const captureEl = this._captureEl;
            const pointerId = this._pointerId;

            // Clear first so nested lostpointercapture / blur can't re-enter cleanup.
            this.dragging = null;
            this._pointerId = null;
            this._captureEl = null;
            this._onMove = null;
            this._onUp = null;

            if (onMove) {
                document.removeEventListener('pointermove', onMove, true);
            }
            if (onUp) {
                document.removeEventListener('pointerup', onUp, true);
                document.removeEventListener('pointercancel', onUp, true);
                document.removeEventListener('lostpointercapture', onUp, true);
                window.removeEventListener('blur', onUp);
            }

            if (captureEl && pointerId != null && captureEl.releasePointerCapture) {
                try {
                    if (captureEl.hasPointerCapture?.(pointerId)) {
                        captureEl.releasePointerCapture(pointerId);
                    }
                } catch {
                    /* ignore */
                }
            }

            document.body.classList.remove('ide-dragging', 'ide-dragging-row');
            this.notifyResize();
        },

        resetPanels() {
            this.left = DEFAULT_PANELS.left;
            this.right = DEFAULT_PANELS.right;
            this.bottom = DEFAULT_PANELS.bottom;
            this.$nextTick(() => this.notifyResize());
        },
    };
};

function scheduleWorkspaceRestore() {
    requestAnimationFrame(() => {
        window.InterviewWorkspace?.restoreDom();
    });
}

document.addEventListener('livewire:navigated', () => {
    window.InterviewWorkspace?.syncDocClass();
    scheduleWorkspaceRestore();
});

document.addEventListener('DOMContentLoaded', () => {
    window.InterviewWorkspace?.syncDocClass();
});

document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => {
        scheduleWorkspaceRestore();
    });
    Livewire.hook('commit', ({ succeed }) => {
        succeed(() => scheduleWorkspaceRestore());
    });
});
