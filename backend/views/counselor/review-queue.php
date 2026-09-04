<?php ob_start(); ?>
<style>
    .rq-page { display: flex; gap: 24px; min-height: calc(100vh - 200px); }
    .rq-left { flex: 1; display: flex; flex-direction: column; gap: 16px; }
    .rq-right { width: 400px; flex-shrink: 0; }

    .rq-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .rq-header h2 { margin: 0; color: #0b1c30; font-size: 24px; font-weight: 700; }

    .rq-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px; }
    .rq-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; text-align: center; cursor: pointer; transition: all 0.15s; text-decoration: none; color: inherit; }
    .rq-stat:hover { border-color: #0054cb; }
    .rq-stat.active { border-color: #0054cb; background: #f0f7ff; }
    .rq-stat__value { font-size: 22px; font-weight: 700; color: #0b1c30; }
    .rq-stat__label { font-size: 11px; color: #73777f; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.3px; }

    .rq-filters { display: flex; gap: 10px; align-items: center; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; }
    .rq-filters input, .rq-filters select { height: 34px; padding: 0 10px; border: 1px solid #d0d5dd; border-radius: 6px; font-size: 13px; color: #344054; background: #fff; }
    .rq-filters input { flex: 1; min-width: 0; }
    .rq-filters input:focus, .rq-filters select:focus { outline: none; border-color: #0054cb; }

    .rq-list { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; flex: 1; }
    .rq-list-header { display: grid; grid-template-columns: 1fr 140px 100px 90px 80px; padding: 10px 16px; background: #fafbfc; border-bottom: 1px solid #e5e7eb; font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; }

    .rq-item { display: grid; grid-template-columns: 1fr 140px 100px 90px 80px; padding: 14px 16px; border-bottom: 1px solid #f3f4f6; align-items: center; cursor: pointer; transition: background 0.1s; text-decoration: none; color: inherit; }
    .rq-item:last-child { border-bottom: none; }
    .rq-item:hover { background: #f9fafb; }
    .rq-item.active { background: #f0f7ff; border-left: 3px solid #0054cb; }

    .rq-student { display: flex; align-items: center; gap: 10px; }
    .rq-avatar { width: 36px; height: 36px; border-radius: 50%; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0; }
    .rq-student-info { min-width: 0; }
    .rq-student-name { font-size: 13px; font-weight: 600; color: #0b1c30; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .rq-student-id { font-size: 11px; color: #73777f; }

    .rq-doc-type { font-size: 13px; color: #43474f; }
    .rq-date { font-size: 12px; color: #73777f; }

    .rq-badge { display: inline-flex; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .rq-badge--pending { background: #fef3c7; color: #d97706; }
    .rq-badge--approved { background: #d1fae5; color: #059669; }
    .rq-badge--rejected { background: #fee2e2; color: #dc2626; }
    .rq-badge--resubmit { background: #dbeafe; color: #2563eb; }

    .rq-review-btn { padding: 5px 12px; border-radius: 6px; border: 1px solid #d0d5dd; background: #fff; font-size: 12px; font-weight: 500; color: #344054; cursor: pointer; }
    .rq-review-btn:hover { background: #f3f4f6; }

    .rq-empty { text-align: center; padding: 40px 20px; color: #9ca3af; font-size: 14px; }

    .rq-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; position: sticky; top: 24px; }
    .rq-panel-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .rq-panel-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .rq-panel-header p { margin: 4px 0 0; font-size: 13px; color: #73777f; }
    .rq-panel-body { padding: 20px; }
    .rq-panel-empty { text-align: center; padding: 60px 20px; color: #9ca3af; font-size: 13px; }

    .rq-preview { background: #f3f4f6; border-radius: 8px; padding: 40px; text-align: center; margin-bottom: 20px; }
    .rq-preview svg { width: 48px; height: 48px; color: #9ca3af; margin-bottom: 8px; }
    .rq-preview p { margin: 0; font-size: 13px; color: #73777f; }

    .rq-doc-info { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
    .rq-doc-row { display: flex; justify-content: space-between; font-size: 13px; }
    .rq-doc-label { color: #73777f; }
    .rq-doc-value { color: #0b1c30; font-weight: 500; }

    .rq-review-form { display: flex; flex-direction: column; gap: 14px; }
    .rq-review-form h4 { margin: 0; font-size: 14px; font-weight: 600; color: #0b1c30; }

    .rq-radio-group { display: flex; flex-direction: column; gap: 8px; }
    .rq-radio { display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 8px; cursor: pointer; transition: all 0.15s; }
    .rq-radio:hover { border-color: #d0d5dd; background: #f9fafb; }
    .rq-radio input[type="radio"] { margin-top: 2px; accent-color: #0054cb; }
    .rq-radio-text { flex: 1; }
    .rq-radio-title { font-size: 13px; font-weight: 500; color: #0b1c30; display: flex; align-items: center; gap: 6px; }
    .rq-radio-desc { font-size: 11px; color: #73777f; margin-top: 2px; }
    .rq-radio-icon { width: 14px; height: 14px; }

    .rq-remarks { width: 100%; min-height: 80px; padding: 10px 12px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 13px; color: #344054; resize: vertical; font-family: inherit; }
    .rq-remarks:focus { outline: none; border-color: #0054cb; }
    .rq-remarks::placeholder { color: #9ca3af; }

    .rq-submit { width: 100%; height: 40px; border-radius: 8px; border: none; font-size: 14px; font-weight: 500; cursor: pointer; }
    .rq-submit--approve { background: #059669; color: #fff; }
    .rq-submit--approve:hover { background: #047857; }
    .rq-submit--reject { background: #dc2626; color: #fff; }
    .rq-submit--reject:hover { background: #b91c1c; }
    .rq-submit--resubmit { background: #2563eb; color: #fff; }
    .rq-submit--resubmit:hover { background: #1d4ed8; }

    @media (max-width: 1024px) {
        .rq-page { flex-direction: column; }
        .rq-right { width: 100%; }
    }
</style>

<div class="rq-page">
    <div class="rq-left">
        <div class="rq-header">
            <h2>Document Review Queue</h2>
        </div>

        <div class="rq-stats">
            <a href="<?php echo url('/counselor/documents/review-queue'); ?>" class="rq-stat <?php echo ($filters['status'] ?? 'pending') === 'pending' ? 'active' : ''; ?>">
                <div class="rq-stat__value"><?php echo e($stats['pending'] ?? 0); ?></div>
                <div class="rq-stat__label">Pending</div>
            </a>
            <a href="<?php echo url('/counselor/documents/review-queue?status=approved'); ?>" class="rq-stat <?php echo ($filters['status'] ?? '') === 'approved' ? 'active' : ''; ?>">
                <div class="rq-stat__value" style="color:#059669;"><?php echo e($stats['approved'] ?? 0); ?></div>
                <div class="rq-stat__label">Approved</div>
            </a>
            <a href="<?php echo url('/counselor/documents/review-queue?status=resubmit'); ?>" class="rq-stat <?php echo ($filters['status'] ?? '') === 'resubmit' ? 'active' : ''; ?>">
                <div class="rq-stat__value" style="color:#2563eb;"><?php echo e($stats['resubmit'] ?? 0); ?></div>
                <div class="rq-stat__label">Resubmit</div>
            </a>
            <a href="<?php echo url('/counselor/documents/review-queue?status=rejected'); ?>" class="rq-stat <?php echo ($filters['status'] ?? '') === 'rejected' ? 'active' : ''; ?>">
                <div class="rq-stat__value" style="color:#dc2626;"><?php echo e($stats['rejected'] ?? 0); ?></div>
                <div class="rq-stat__label">Rejected</div>
            </a>
        </div>

        <form class="rq-filters" method="GET" action="<?php echo url('/counselor/documents/review-queue'); ?>">
            <input type="hidden" name="status" value="<?php echo e($filters['status'] ?? 'pending'); ?>">
            <input type="text" name="search" placeholder="Search students..." value="<?php echo e($filters['search'] ?? ''); ?>">
            <select name="category">
                <option value="">All Categories</option>
                <option value="education" <?php echo ($filters['category'] ?? '') === 'education' ? 'selected' : ''; ?>>Education</option>
                <option value="visa" <?php echo ($filters['category'] ?? '') === 'visa' ? 'selected' : ''; ?>>Visa</option>
            </select>
        </form>

        <div class="rq-list">
            <div class="rq-list-header">
                <span>Student</span>
                <span>Document Type</span>
                <span>Uploaded</span>
                <span>Status</span>
                <span>Action</span>
            </div>
            <?php if (!empty($documents)): ?>
                <?php foreach ($documents as $doc): ?>
                    <a href="<?php echo url('/counselor/documents/review-queue?status=' . ($filters['status'] ?? 'pending') . '&doc_id=' . $doc['id']); ?>" class="rq-item <?php echo ($selectedDoc['id'] ?? null) == $doc['id'] ? 'active' : ''; ?>">
                        <div class="rq-student">
                            <div class="rq-avatar"><?php echo e(substr($doc['student_name'] ?? 'NA', 0, 2)); ?></div>
                            <div class="rq-student-info">
                                <div class="rq-student-name"><?php echo e($doc['student_name'] ?? 'Unknown'); ?></div>
                                <div class="rq-student-id">ID: <?php echo e($doc['student_code'] ?? '-'); ?></div>
                            </div>
                        </div>
                        <div class="rq-doc-type"><?php echo e($doc['name']); ?></div>
                        <div class="rq-date"><?php echo e(date('M d, Y', strtotime($doc['created_at']))); ?></div>
                        <div><span class="rq-badge rq-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></div>
                        <div><span class="rq-review-btn">Review</span></div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="rq-empty">No documents found.</div>
            <?php endif; ?>
        </div>
    </div>

    <div class="rq-right">
        <?php if ($selectedDoc): ?>
            <div class="rq-panel">
                <div class="rq-panel-header">
                    <h3><?php echo e($selectedDoc['name']); ?></h3>
                    <p><?php echo e($selectedDoc['student_name'] ?? 'Unknown'); ?> &bull; Uploaded <?php echo e(date('M d', strtotime($selectedDoc['created_at']))); ?></p>
                </div>
                <div class="rq-panel-body">
                    <?php if (!empty($selectedDoc['file_path'])): ?>
                        <?php $fileUrl = url($selectedDoc['file_path']); ?>
                        <?php $fileExt = strtolower(pathinfo($selectedDoc['file_path'], PATHINFO_EXTENSION)); ?>
                        <?php if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                            <div class="rq-preview" style="padding:12px;">
                                <img src="<?php echo e($fileUrl); ?>" alt="<?php echo e($selectedDoc['name']); ?>" style="max-width:100%;max-height:300px;border-radius:6px;object-fit:contain;">
                            </div>
                        <?php elseif ($fileExt === 'pdf'): ?>
                            <div class="rq-preview">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                <p>PDF Document</p>
                                <a href="<?php echo e($fileUrl); ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:6px 14px;border-radius:6px;background:#0054cb;color:#fff;font-size:12px;font-weight:500;text-decoration:none;">Open PDF</a>
                            </div>
                        <?php else: ?>
                            <div class="rq-preview">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                <p>Preview not available</p>
                                <a href="<?php echo e($fileUrl); ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:6px 14px;border-radius:6px;background:#0054cb;color:#fff;font-size:12px;font-weight:500;text-decoration:none;">Download File</a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="rq-preview">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            <p>No file uploaded yet</p>
                        </div>
                    <?php endif; ?>

                    <div class="rq-doc-info">
                        <div class="rq-doc-row"><span class="rq-doc-label">Category</span><span class="rq-doc-value"><?php echo e(ucfirst($selectedDoc['category'])); ?></span></div>
                        <div class="rq-doc-row"><span class="rq-doc-label">Type</span><span class="rq-doc-value"><?php echo e(strtoupper($selectedDoc['type'])); ?></span></div>
                        <div class="rq-doc-row"><span class="rq-doc-label">Size</span><span class="rq-doc-value"><?php echo e(round($selectedDoc['size'] / 1024)); ?> KB</span></div>
                        <div class="rq-doc-row"><span class="rq-doc-label">Status</span><span class="rq-doc-value"><span class="rq-badge rq-badge--<?php echo e($selectedDoc['status']); ?>"><?php echo e(ucfirst($selectedDoc['status'])); ?></span></span></div>
                    </div>

                    <?php if (in_array($selectedDoc['status'] ?? '', ['pending', 'resubmit'], true)): ?>
                        <form class="rq-review-form" method="POST" action="<?php echo url('/counselor/documents/' . $selectedDoc['id'] . '/review'); ?>">
                            <?php echo csrf_field(); ?>
                            <h4>Review Decision</h4>
                            <div class="rq-radio-group">
                                <label class="rq-radio">
                                    <input type="radio" name="status" value="approved">
                                    <div class="rq-radio-text">
                                        <div class="rq-radio-title">
                                            <svg class="rq-radio-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#059669"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            Verify & Approve
                                        </div>
                                        <div class="rq-radio-desc">Document meets all requirements</div>
                                    </div>
                                </label>
                                <label class="rq-radio">
                                    <input type="radio" name="status" value="resubmit">
                                    <div class="rq-radio-text">
                                        <div class="rq-radio-title">
                                            <svg class="rq-radio-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#2563eb"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" /></svg>
                                            Request Resubmission
                                        </div>
                                        <div class="rq-radio-desc">Document is blurry, incomplete, or incorrect</div>
                                    </div>
                                </label>
                                <label class="rq-radio">
                                    <input type="radio" name="status" value="rejected">
                                    <div class="rq-radio-text">
                                        <div class="rq-radio-title">
                                            <svg class="rq-radio-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#dc2626"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            Reject
                                        </div>
                                        <div class="rq-radio-desc">Fraudulent or fundamentally incorrect document</div>
                                    </div>
                                </label>
                            </div>
                            <?php if (!empty($_SESSION['errors']['status'])): ?><div class="form-error" style="margin-top:8px;"><?php echo e($_SESSION['errors']['status']); ?></div><?php endif; ?>
                            <textarea class="rq-remarks" name="remarks" placeholder="Add specific notes about the decision (sent to student)..."></textarea>
                            <?php if (!empty($_SESSION['errors']['remarks'])): ?><div class="form-error"><?php echo e($_SESSION['errors']['remarks']); ?></div><?php endif; ?>
                            <button type="submit" class="rq-submit rq-submit--approve" id="reviewSubmitBtn">Submit Review</button>
                        </form>
                        <?php unset($_SESSION['errors'], $_SESSION['old']); ?>
                        <script>
                        document.querySelectorAll('input[name="status"]').forEach(function(radio) {
                            radio.addEventListener('change', function() {
                                var btn = document.getElementById('reviewSubmitBtn');
                                btn.className = 'rq-submit';
                                if (this.value === 'approved') { btn.classList.add('rq-submit--approve'); btn.textContent = 'Approve Document'; }
                                else if (this.value === 'rejected') { btn.classList.add('rq-submit--reject'); btn.textContent = 'Reject Document'; }
                                else if (this.value === 'resubmit') { btn.classList.add('rq-submit--resubmit'); btn.textContent = 'Request Resubmission'; }
                            });
                        });
                        </script>
                    <?php else: ?>
                        <div style="padding:16px;background:#f9fafb;border-radius:8px;text-align:center;">
                            <p style="margin:0;font-size:13px;color:#73777f;">This document has already been <strong><?php echo e($selectedDoc['status']); ?></strong>.</p>
                            <?php if (!empty($selectedDoc['remarks'])): ?>
                                <p style="margin:8px 0 0;font-size:13px;color:#43474f;font-style:italic;">"<?php echo e($selectedDoc['remarks']); ?>"</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="rq-panel">
                <div class="rq-panel-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="margin:0 auto 12px;display:block;color:#d0d5dd;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    <p>Select a document from the list to review</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/counselor-layout.php';
