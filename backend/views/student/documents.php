<?php
$pageTitle = $pageTitle ?? 'My Documents';
$pageDescription = $pageDescription ?? 'Your uploaded documents.';
$currentPage = $currentPage ?? 'documents';
$assetPath = url('/frontend/assets');
$documents = $documents ?? [];
ob_start();
?>
<style>
.page-header{margin-bottom:24px}
.page-header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.page-header p{margin:4px 0 0;color:#73777f;font-size:14px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,0.04);overflow:hidden}
.card__body{padding:20px}
.card__title{font-size:15px;font-weight:600;color:#0b1c30;margin-bottom:4px}
.card__sub{font-size:13px;color:#73777f;margin-bottom:4px}
.card__meta{margin-bottom:12px;display:flex;flex-wrap:wrap;gap:6px;align-items:center}
.preview-frame{border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#f9fafb;margin-bottom:12px}
.preview-media{display:block;margin:0 auto;max-width:100%;max-height:280px}
.preview-placeholder{display:flex;align-items:center;justify-content:center;min-height:100px;border:1px dashed #d0d5dd;border-radius:8px;background:#f9fafb;font-size:13px;color:#73777f;margin-bottom:12px}
.badge{display:inline-flex;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:500}
.badge--education{background:#dbeafe;color:#1d4ed8}
.badge--visa{background:#fef3c7;color:#d97706}
.badge--assigned{background:#e0e7ff;color:#4338ca}
.badge--pending{background:#fef3c7;color:#d97706}
.badge--approved{background:#d1fae5;color:#059669}
.badge--rejected{background:#fee2e2;color:#dc2626}
.badge--resubmit{background:#dbeafe;color:#2563eb}
.stage{margin-bottom:6px}
.stage-label{font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:#73777f;margin-bottom:4px}
.stage-text{font-size:13px;color:#43474f;line-height:1.5}
.hidden-input{display:none}
.submit-form{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.file-input{flex:1;min-width:180px;font-size:13px}
.submit-btn{height:38px;padding:0 20px;border-radius:8px;background:#0054cb;color:#fff;font-size:14px;font-weight:500;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:6px}
.submit-btn:hover{background:#004aaf}
.remarks{margin-top:12px;padding:10px 14px;border-radius:8px;background:#fef2f2;border:1px solid #fecaca;font-size:13px;color:#dc2626}
.remarks strong{display:block;font-size:12px;margin-bottom:2px;text-transform:uppercase;letter-spacing:0.5px}
.link{font-size:13px;color:#0054cb;font-weight:500;text-decoration:none}
.link:hover{text-decoration:underline}
.empty{text-align:center;padding:40px 24px;color:#9ca3af;font-size:14px;background:#fff;border:1px solid #e5e7eb;border-radius:12px}
@media(max-width:640px){.grid{grid-template-columns:1fr}}
</style>
<div class="page-header">
    <h2>My Documents</h2>
    <p>Track the documents required for your application.</p>
</div>
<?php if (!empty($documents)): ?>
    <div class="grid">
        <?php foreach ($documents as $doc): ?>
            <?php
            $filePath = $doc['file_path'] ?? '';
            $fileClean = '/' . ltrim($filePath, '/');
            $fileUrl = $filePath !== '' ? url($fileClean) : '';
            $diskPath = $filePath !== '' ? BASE_PATH . $fileClean : '';
            $fileExists = $diskPath !== '' && is_file($diskPath);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
            $isPdf = $ext === 'pdf';
            $needsSubmission = in_array($doc['status'], ['assigned', 'resubmit']);
            ?>
            <div class="card">
                <div class="card__body">
                    <div class="card__title"><?php echo e($doc['name']); ?></div>
                    <div class="card__sub"><?php echo e($doc['student_code'] ?? ''); ?></div>
                    <div class="card__meta">
                        <span class="badge badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span>
                        <span class="badge badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span>
                        <?php if ($doc['assigned_at']): ?>
                            <span style="color:#73777f;font-size:12px;">Required since <?php echo e(date('M d, Y', strtotime($doc['assigned_at']))); ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if ($needsSubmission): ?>
                        <div class="stage">
                            <div class="stage-label"><?php echo $doc['status'] === 'resubmit' ? 'Stage 3 - Revised Submission Needed' : 'Stage 1 - Required'; ?></div>
                            <div class="stage-text">
                                <?php if ($doc['status'] === 'resubmit'): ?>
                                    Your document was reviewed and needs a revision. Upload your updated file below.
                                <?php else: ?>
                                    Assigner <?php echo e($doc['assigned_by_name'] ?? 'requested'); ?> this document. Please upload the file to proceed.
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($doc['remarks'])): ?>
                                <div class="remarks"><strong>Reviewer's note</strong><?php echo e($doc['remarks']); ?></div>
                            <?php endif; ?>
                        </div>

                        <form class="submit-form" method="POST" action="<?php echo url('/student/documents/' . $doc['id'] . '/submit'); ?>" enctype="multipart/form-data">
                            <input class="file-input" type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.bmp,.svg">
                            <button class="submit-btn" type="submit"><?php echo $doc['status'] === 'resubmit' ? 'Resubmit' : 'Submit'; ?></button>
                        </form>
                    <?php else: ?>
                        <?php if ($fileUrl !== '' && $fileExists && $isPdf): ?>
                            <div class="preview-frame">
                                <iframe src="<?php echo e($fileUrl); ?>" width="100%" height="280" style="border:none;background:#fff;" title="Document preview"></iframe>
                            </div>
                        <?php elseif ($fileUrl !== '' && $fileExists && $isImage): ?>
                            <div class="preview-frame">
                                <img src="<?php echo e($fileUrl); ?>" alt="<?php echo e($doc['name']); ?>" class="preview-media">
                            </div>
                        <?php else: ?>
                            <div class="preview-placeholder">Preview not available</div>
                        <?php endif; ?>

                        <?php if ($doc['status'] === 'pending'): ?>
                            <div class="stage">
                                <div class="stage-label">Stage 2 - Awaiting Review</div>
                                <div class="stage-text">Submitted on <?php echo e(date('M d, Y g:i A', strtotime($doc['submitted_at']))); ?>. An administrator is reviewing your document.</div>
                            </div>
                        <?php elseif ($doc['status'] === 'approved'): ?>
                            <div class="stage">
                                <div class="stage-label">Stage 4 - Approved</div>
                                <div class="stage-text">Your document was approved<?php echo $doc['reviewed_at'] ? ' on ' . e(date('M d, Y', strtotime($doc['reviewed_at']))) : ''; ?><?php echo $doc['reviewed_by_name'] ? ' by ' . e($doc['reviewed_by_name']) : ''; ?>.</div>
                                <?php if (!empty($doc['remarks'])): ?><div class="remarks" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#16a34a;"><strong>Reviewer's note</strong><?php echo e($doc['remarks']); ?></div><?php endif; ?>
                            </div>
                        <?php elseif ($doc['status'] === 'rejected'): ?>
                            <div class="stage">
                                <div class="stage-label">Stage 3 - Rejected</div>
                                <div class="stage-text">Your document was rejected<?php echo $doc['reviewed_at'] ? ' on ' . e(date('M d, Y', strtotime($doc['reviewed_at']))) : ''; ?>. Please contact your counselor for guidance.</div>
                                <?php if (!empty($doc['remarks'])): ?><div class="remarks"><strong>Reviewer's note</strong><?php echo e($doc['remarks']); ?></div><?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($fileUrl !== ''): ?>
                            <a class="link" href="<?php echo e($fileUrl); ?>" target="_blank" rel="noopener">Open file</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty">No documents assigned yet. Your counselor will assign required documents here.</div>
<?php endif; ?>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/student-layout.php';