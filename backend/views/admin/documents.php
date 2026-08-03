<?php
$pageTitle = 'Documents';
$pageDescription = 'Manage institutional templates, reports, and shared resources.';
$currentPage = 'documents';

$documents = [
    [
        'name' => 'Q3_Counseling_Report_2023.pdf',
        'size' => '2.4 MB',
        'type' => 'pdf',
        'category' => 'Report',
        'categoryTone' => 'report',
        'uploadedBy' => 'Sarah Jenkins',
        'date' => 'Oct 12, 2023',
    ],
    [
        'name' => 'Student_Intake_Form_Template.docx',
        'size' => '156 KB',
        'type' => 'docx',
        'category' => 'Template',
        'categoryTone' => 'template',
        'uploadedBy' => 'System Admin',
        'date' => 'Sep 05, 2023',
    ],
    [
        'name' => 'University_Acceptance_Rates_2023.xlsx',
        'size' => '4.1 MB',
        'type' => 'xlsx',
        'category' => 'Data',
        'categoryTone' => 'data',
        'uploadedBy' => 'Dr. Emily Chen',
        'date' => 'Aug 22, 2023',
    ],
];

ob_start();
?>
<style>
    .doc-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .doc-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
    }

    .doc-header h2 {
        margin: 0;
        color: #0b1c30;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;
        letter-spacing: -0.64px;
    }

    .doc-header p {
        margin: 4px 0 0;
        color: #73777f;
        font-size: 14px;
        line-height: 1.5;
    }

    .doc-header-actions {
        display: flex;
        gap: 12px;
    }

    .doc-btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 20px;
        border: 1px solid #c3c6d0;
        border-radius: 8px;
        background: #fff;
        color: #43474f;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
    }

    .doc-btn-filter:hover {
        background: #f9fafb;
    }

    .doc-btn-filter svg {
        width: 16px;
        height: 16px;
    }

    .doc-btn-upload {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 20px;
        border-radius: 8px;
        background: #0054cb;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.15s;
        box-shadow: 0 1px 2px rgba(0,84,203,0.2);
    }

    .doc-btn-upload:hover {
        background: #004aaf;
    }

    .doc-btn-upload svg {
        width: 16px;
        height: 16px;
    }

    .doc-table-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .doc-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
    }

    .doc-view-toggle {
        display: flex;
        gap: 4px;
    }

    .doc-view-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #9ca3af;
        cursor: pointer;
        transition: all 0.15s;
    }

    .doc-view-btn:hover {
        background: #f3f4f6;
        color: #6b7280;
    }

    .doc-view-btn--active {
        background: #e8f0fe;
        color: #0054cb;
    }

    .doc-view-btn--active:hover {
        background: #d6e4fd;
    }

    .doc-view-btn svg {
        width: 18px;
        height: 18px;
    }

    .doc-table-count {
        font-size: 13px;
        color: #73777f;
    }

    .doc-table-wrap {
        overflow-x: auto;
    }

    .doc-table {
        width: 100%;
        border-collapse: collapse;
    }

    .doc-table th {
        padding: 12px 24px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #9ca3af;
        background: #fafbfc;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .doc-table th:last-child {
        text-align: right;
    }

    .doc-table td {
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #43474f;
        vertical-align: middle;
    }

    .doc-table tbody tr:last-child td {
        border-bottom: none;
    }

    .doc-table tbody tr:hover {
        background: #fafbff;
    }

    .doc-checkbox {
        width: 18px;
        height: 18px;
        border: 1.5px solid #c3c6d0;
        border-radius: 4px;
        cursor: pointer;
        accent-color: #0054cb;
    }

    .doc-name {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .doc-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .doc-icon--pdf {
        background: #fef2f2;
        color: #dc2626;
    }

    .doc-icon--docx {
        background: #eff6ff;
        color: #2563eb;
    }

    .doc-icon--xlsx {
        background: #f0fdf4;
        color: #16a34a;
    }

    .doc-icon svg {
        width: 18px;
        height: 18px;
    }

    .doc-name__text {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .doc-name__title {
        font-size: 14px;
        font-weight: 600;
        color: #0b1c30;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .doc-name__size {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .doc-category {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .doc-category--report {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .doc-category--template {
        background: #e5e7eb;
        color: #374151;
    }

    .doc-category--data {
        background: #e5e7eb;
        color: #6b7280;
    }

    .doc-uploader {
        color: #43474f;
        white-space: nowrap;
    }

    .doc-date {
        color: #73777f;
        white-space: nowrap;
    }

    .doc-actions {
        display: flex;
        gap: 4px;
        justify-content: flex-end;
    }

    .doc-action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        background: none;
        cursor: pointer;
        transition: background 0.15s;
    }

    .doc-action-btn:hover {
        background: #f3f4f6;
    }

    .doc-action-btn svg {
        width: 16px;
        height: 16px;
    }

    .doc-action-btn--view svg { color: #2563eb; }
    .doc-action-btn--edit svg { color: #6b7280; }
    .doc-action-btn--delete svg { color: #ef4444; }

    .doc-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .doc-pagination-info {
        font-size: 13px;
        color: #73777f;
    }

    .doc-pagination-pages {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .doc-pagination-arrow {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #9ca3af;
        cursor: pointer;
        transition: background 0.15s;
    }

    .doc-pagination-arrow:hover {
        background: #f3f4f6;
    }

    .doc-pagination-arrow:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .doc-pagination-arrow svg {
        width: 14px;
        height: 14px;
    }

    .doc-pagination-page {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.15s;
    }

    .doc-pagination-page:hover {
        background: #f3f4f6;
    }

    .doc-pagination-page--active {
        background: #0054cb;
        color: #fff;
    }

    .doc-pagination-page--active:hover {
        background: #004aaf;
    }

    .doc-pagination-ellipsis {
        color: #9ca3af;
        font-size: 14px;
        padding: 0 4px;
    }

    @media (max-width: 768px) {
        .doc-header {
            flex-direction: column;
            gap: 12px;
        }

        .doc-header-actions {
            width: 100%;
        }

        .doc-btn-filter,
        .doc-btn-upload {
            flex: 1;
        }

        .doc-pagination {
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
        }

        .doc-pagination-nav {
            justify-content: space-between;
        }
    }
</style>

<div class="doc-page">
    <section class="doc-header" aria-labelledby="doc-title">
        <div>
            <h2 id="doc-title">Documents</h2>
            <p>Manage institutional templates, reports, and shared resources.</p>
        </div>
        <div class="doc-header-actions">
            <button type="button" class="doc-btn-filter">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="2" y1="4" x2="14" y2="4"/>
                    <line x1="4" y1="8" x2="12" y2="8"/>
                    <line x1="6" y1="12" x2="10" y2="12"/>
                </svg>
                Filter
            </button>
            <button type="button" class="doc-btn-upload">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/>
                    <polyline points="9 15 12 12 15 15"/>
                </svg>
                Upload Document
            </button>
        </div>
    </section>

    <section class="doc-table-card" aria-label="Document list">
        <div class="doc-table-toolbar">
            <div class="doc-view-toggle">
                <button type="button" class="doc-view-btn doc-view-btn--active" aria-label="List view">
                    <svg viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <line x1="2" y1="4" x2="16" y2="4"/>
                        <line x1="2" y1="9" x2="16" y2="9"/>
                        <line x1="2" y1="14" x2="16" y2="14"/>
                    </svg>
                </button>
                <button type="button" class="doc-view-btn" aria-label="Grid view">
                    <svg viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <rect x="2" y="2" width="6" height="6" rx="1"/>
                        <rect x="10" y="2" width="6" height="6" rx="1"/>
                        <rect x="2" y="10" width="6" height="6" rx="1"/>
                        <rect x="10" y="10" width="6" height="6" rx="1"/>
                    </svg>
                </button>
            </div>
            <span class="doc-table-count">Showing 1-10 of 42 items</span>
        </div>

        <div class="doc-table-wrap">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th style="width:48px"><input type="checkbox" class="doc-checkbox" aria-label="Select all"></th>
                        <th>Document Name</th>
                        <th>Category</th>
                        <th>Uploaded By</th>
                        <th>Date</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                        <tr>
                            <td><input type="checkbox" class="doc-checkbox" aria-label="Select <?php echo e($doc['name']); ?>"></td>
                            <td>
                                <div class="doc-name">
                                    <span class="doc-icon doc-icon--<?php echo e($doc['type']); ?>">
                                        <?php if ($doc['type'] === 'pdf'): ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                            </svg>
                                        <?php elseif ($doc['type'] === 'docx'): ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <polyline points="14 2 14 8 20 8"/>
                                                <line x1="8" y1="13" x2="16" y2="13"/>
                                                <line x1="8" y1="17" x2="12" y2="17"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <line x1="3" y1="9" x2="21" y2="9"/>
                                                <line x1="3" y1="15" x2="21" y2="15"/>
                                                <line x1="9" y1="3" x2="9" y2="21"/>
                                                <line x1="15" y1="3" x2="15" y2="21"/>
                                            </svg>
                                        <?php endif; ?>
                                    </span>
                                    <div class="doc-name__text">
                                        <span class="doc-name__title"><?php echo e($doc['name']); ?></span>
                                        <span class="doc-name__size"><?php echo e($doc['size']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="doc-category doc-category--<?php echo e($doc['categoryTone']); ?>"><?php echo e($doc['category']); ?></span>
                            </td>
                            <td class="doc-uploader"><?php echo e($doc['uploadedBy']); ?></td>
                            <td class="doc-date"><?php echo e($doc['date']); ?></td>
                            <td>
                                <div class="doc-actions">
                                    <button type="button" class="doc-action-btn doc-action-btn--view" aria-label="View document">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    </button>
                                    <button type="button" class="doc-action-btn doc-action-btn--edit" aria-label="Edit document">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                    </button>
                                    <button type="button" class="doc-action-btn doc-action-btn--delete" aria-label="Delete document">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="doc-pagination">
            <span class="doc-pagination-info">Showing 1 to 8 of 132 documents</span>

            <div class="doc-pagination-pages">
                <button type="button" class="doc-pagination-arrow" disabled>
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3L5 7L9 11"/></svg>
                </button>
                <button type="button" class="doc-pagination-page doc-pagination-page--active">1</button>
                <button type="button" class="doc-pagination-page">2</button>
                <button type="button" class="doc-pagination-page">3</button>
                <span class="doc-pagination-ellipsis">...</span>
                <button type="button" class="doc-pagination-arrow">
                    <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3L9 7L5 11"/></svg>
                </button>
            </div>
        </div>
    </section>
</div>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layouts/admin-layout.php';
