<div class="report-ttd d-none d-print-block mt-5 pt-3">
    <div class="d-flex justify-content-between text-center px-4 mt-5" style="font-family: Arial, sans-serif;">
        <div style="width: 30%;">
            <p class="mb-5 pb-4">Mengetahui,<br><b><?= e($settings['jabatan_ketua'] ?? 'Ketua') ?></b></p>
            <p class="mb-0 underline" style="border-bottom: 1px solid #000; display: inline-block; padding-bottom: 2px;"><b><?= e($settings['ketua'] ?? 'Nama Ketua') ?></b></p>
        </div>
        <div style="width: 30%;">
            <p class="mb-5 pb-4"><br><b><?= e($settings['jabatan_sekretaris'] ?? 'Sekretaris') ?></b></p>
            <p class="mb-0 underline" style="border-bottom: 1px solid #000; display: inline-block; padding-bottom: 2px;"><b><?= e($settings['sekretaris'] ?? 'Nama Sekretaris') ?></b></p>
        </div>
        <div style="width: 30%;">
            <p class="mb-5 pb-4"><?= e(date('d F Y', strtotime($reportEndDate ?? date('Y-m-d')))) ?>,<br><b><?= e($settings['jabatan_bendahara'] ?? 'Bendahara') ?></b></p>
            <p class="mb-0 underline" style="border-bottom: 1px solid #000; display: inline-block; padding-bottom: 2px;"><b><?= e($settings['bendahara'] ?? 'Nama Bendahara') ?></b></p>
        </div>
    </div>
</div>
