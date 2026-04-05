<style>
/* ============================================================
   SHARED REPORT STYLES - Mobile-first, responsive, premium
   ============================================================ */

/* --- Report page layout --- */
.report-page { max-width: 100%; }

/* --- Report header bar --- */
.report-header-bar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .75rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
}
.report-header-bar h5 {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
}
.report-header-bar .period-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 20px;
    padding: .28rem .85rem;
    font-size: .78rem;
    font-weight: 600;
    white-space: nowrap;
}

/* --- Stat summary mini cards (colored gradient) --- */
.rpt-stat {
    border-radius: 14px;
    padding: 1.1rem 1.2rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(0,0,0,.1);
    transition: transform .2s;
}
.rpt-stat:hover { transform: translateY(-2px); }
.rpt-stat.green  { background: linear-gradient(135deg, #10b981, #059669); }
.rpt-stat.red    { background: linear-gradient(135deg, #ef4444, #dc2626); }
.rpt-stat.blue   { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.rpt-stat.slate  { background: linear-gradient(135deg, #475569, #334155); }
.rpt-stat.purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
.rpt-stat.teal   { background: linear-gradient(135deg, #0d9488, #0f766e); }

.rpt-stat .rpt-icon {
    width: 42px; height: 42px; border-radius: 50%;
    background: rgba(255,255,255,.22);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.rpt-stat .rpt-bg-icon {
    position: absolute;
    font-size: 6rem; right: -.5rem; bottom: -1.2rem;
    opacity: .1; line-height: 1;
}
.rpt-stat h3 { font-size: 1.35rem; font-weight: 700; color: #fff; margin: .35rem 0 .15rem; }
.rpt-stat p  { color: rgba(255,255,255,.8); margin: 0; font-size: .78rem; }
.rpt-stat label { color: rgba(255,255,255,.85); font-size: .72rem; letter-spacing: .5px; text-transform: uppercase; font-weight: 600; }

/* --- Section cards --- */
.rpt-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border: 1px solid #f1f5f9;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.rpt-card-header {
    padding: .85rem 1.25rem;
    background: #f8fafc;
    border-bottom: 1px solid #e9ecef;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.rpt-card-header h6 {
    font-size: .9rem; font-weight: 700; color: #1e293b; margin: 0;
    display: flex; align-items: center; gap: .5rem;
}
.rpt-card-body { padding: 1.1rem 1.25rem; }
.rpt-card-body.no-pad { padding: 0; }

/* --- Colored section header strip --- */
.rpt-strip {
    font-size: .75rem; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; color: #fff;
    padding: .55rem 1rem; border-radius: 0;
    display: block; text-align: center;
}
.rpt-strip.green { background: #10b981; }
.rpt-strip.blue  { background: #3b82f6; }
.rpt-strip.dark  { background: #1e293b; }

/* --- Tables --- */
.rpt-table { width: 100%; border-collapse: collapse; }
.rpt-table th {
    font-size: .75rem; font-weight: 700; color: #64748b;
    letter-spacing: .5px; text-transform: uppercase;
    padding: .65rem .85rem; border-bottom: 2px solid #e2e8f0;
    background: #f8fafc; white-space: nowrap;
}
.rpt-table td {
    padding: .65rem .85rem; border-bottom: 1px solid #f1f5f9;
    font-size: .88rem; color: #1e293b; vertical-align: middle;
}
.rpt-table tbody tr:hover { background: #f8fafc; }
.rpt-table tfoot td {
    font-weight: 700; background: #f1f5f9;
    border-top: 2px solid #e2e8f0; padding: .75rem .85rem;
}

/* --- Progress bar custom --- */
.rpt-progress {
    height: 8px; border-radius: 4px; background: #e2e8f0; overflow: hidden;
}
.rpt-progress-bar {
    height: 100%; border-radius: 4px; transition: width .4s;
}
.rpt-progress-bar.green  { background: #10b981; }
.rpt-progress-bar.yellow { background: #f59e0b; }
.rpt-progress-bar.red    { background: #ef4444; }

/* --- Tab nav pills --- */
.rpt-pills {
    background: #f1f5f9;
    border-radius: 12px;
    padding: .3rem;
    display: flex; gap: .25rem;
    overflow-x: auto;
    flex-wrap: nowrap;
}
.rpt-pill {
    flex: 1; min-width: max-content;
    text-align: center; padding: .5rem .9rem;
    border-radius: 10px; border: none; background: transparent;
    font-size: .83rem; font-weight: 600; color: #64748b;
    cursor: pointer; white-space: nowrap; transition: all .2s;
}
.rpt-pill.active { background: #fff; color: #1d4ed8; box-shadow: 0 1px 6px rgba(0,0,0,.1); }

/* --- Total bar (dark) --- */
.rpt-total-bar {
    background: linear-gradient(135deg, #1e293b, #334155);
    color: #fff; padding: 1rem 1.25rem; text-align: center;
}
.rpt-total-bar small {
    display: block; font-size: .68rem; letter-spacing: 1px;
    text-transform: uppercase; opacity: .7; margin-bottom: .2rem;
}
.rpt-total-bar h4 { font-size: 1.6rem; font-weight: 800; color: #fff; margin: 0; }

/* --- Action buttons row --- */
.rpt-actions {
    display: flex; flex-wrap: wrap; gap: .6rem; margin-bottom: 1.25rem;
}
.rpt-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .45rem 1rem; border-radius: 10px; font-size: .82rem;
    font-weight: 600; border: none; cursor: pointer; text-decoration: none;
    transition: all .2s;
}
.rpt-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
.rpt-btn.excel  { background: #16a34a; color: #fff; }
.rpt-btn.pdf    { background: #dc2626; color: #fff; }
.rpt-btn.print  { background: #475569; color: #fff; }

/* --- Empty state --- */
.rpt-empty {
    text-align: center; padding: 2.5rem 1rem; color: #94a3b8;
}
.rpt-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
.rpt-empty p { margin: 0; font-size: .9rem; }

/* --- Mobile responsive --- */
@media (max-width: 575.98px) {
    .rpt-stat { padding: .9rem 1rem; }
    .rpt-stat h3 { font-size: 1.1rem; }
    .rpt-card-body { padding: .85rem 1rem; }
    .rpt-table th, .rpt-table td { padding: .5rem .6rem; font-size: .82rem; }
    .rpt-total-bar h4 { font-size: 1.25rem; }
}

/* --- Print --- */
@media print {
    .rpt-actions, .no-print, .d-print-none { display: none !important; }
    .rpt-stat { box-shadow: none !important; }
    .rpt-card { box-shadow: none !important; border: 1px solid #ccc !important; }
    .rpt-total-bar { background: #333 !important; -webkit-print-color-adjust: exact; }
}
</style>
