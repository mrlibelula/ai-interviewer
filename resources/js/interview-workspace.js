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
            // Explicit classic choice wins; otherwise default to IDE
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

window.createInterviewWorkspace = function createInterviewWorkspace() {
    const panels = window.InterviewWorkspace.getPanels();

    return {
        layout: window.InterviewWorkspace.get(),
        mobileTab: 'code',
        left: panels.left,
        right: panels.right,
        bottom: panels.bottom,
        dragging: null,
        _onMove: null,
        _onUp: null,
        _onExternal: null,

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

            this.$watch('layout', (value) => {
                this.ensureEditorVisible();
                // Persist without re-dispatching into ourselves when already in sync
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

            this.$watch('left', () => this.persistPanels());
            this.$watch('right', () => this.persistPanels());
            this.$watch('bottom', () => this.persistPanels());

            this.$nextTick(() => this.notifyResize());
        },

        destroy() {
            if (this._onExternal) {
                window.removeEventListener('workspace-layout-changed', this._onExternal);
            }
            this.stopDrag();
            // Only clear if this workspace is leaving the page
            queueMicrotask(() => {
                if (!document.querySelector('.interview-workspace')) {
                    document.documentElement.classList.remove('interview-ide');
                }
            });
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
            event.preventDefault();
            this.dragging = axis;
            this._onMove = (e) => this.onDrag(e);
            this._onUp = () => this.stopDrag();
            window.addEventListener('pointermove', this._onMove);
            window.addEventListener('pointerup', this._onUp);
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
            this.dragging = null;
            if (this._onMove) window.removeEventListener('pointermove', this._onMove);
            if (this._onUp) window.removeEventListener('pointerup', this._onUp);
            this._onMove = null;
            this._onUp = null;
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

document.addEventListener('livewire:navigated', () => {
    window.InterviewWorkspace?.syncDocClass();
});

document.addEventListener('DOMContentLoaded', () => {
    window.InterviewWorkspace?.syncDocClass();
});
