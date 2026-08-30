<?php
ob_start();
?>
<style>
    .detail-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 32px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); max-width: 720px; }
    .detail-card h3 { margin: 0 0 24px; font-size: 18px; font-weight: 700; color: #0b1c30; }
    .detail-row { display: flex; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { width: 180px; font-size: 14px; font-weight: 500; color: #73777f; }
    .detail-value { font-size: 14px; color: #0b1c30; }
    .detail-actions { display: flex; gap: 12px; margin-top: 24px; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #0054cb; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; text-decoration: none; }
    .btn-secondary { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #fff; color: #43474f; border: 1px solid #e5e7eb; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; }
    .btn-danger { display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 24px; border-radius: 8px; background: #ef4444; color: #fff; font-size: 14px; font-weight: 500; border: none; cursor: pointer; }

    .section-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; max-width: 720px; margin-top: 24px; }
    .section-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; }
    .section-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #0b1c30; }
    .section-count { font-size: 13px; color: #73777f; font-weight: 400; }

    .inq-table { width: 100%; border-collapse: collapse; }
    .inq-table th { padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.5px; background: #fafbfc; border-bottom: 1px solid #e5e7eb; }
    .inq-table td { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #43474f; }
    .inq-table tbody tr:last-child td { border-bottom: none; }
    .inq-table tbody tr:hover { background: #fafbff; }

    .inq-id { color: #0054cb; font-weight: 600; text-decoration: none; }
    .inq-id:hover { text-decoration: underline; }
    .inq-date { font-size: 13px; color: #73777f; }
    .inq-badge { display: inline-flex; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
    .inq-badge--new { background: #fef3c7; color: #d97706; }
    .inq-badge--assigned { background: #dbeafe; color: #2563eb; }
    .inq-badge--in-progress { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }
    .inq-badge--closed { background: #f3f4f6; color: #6b7280; }

    .inq-empty { text-align: center; padding: 32px 20px; color: #9ca3af; font-size: 14px; }

    .doc-badge { display: inline-flex; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
    .doc-badge--education { background: #dbeafe; color: #1d4ed8; }
    .doc-badge--visa { background: #fef3c7; color: #d97706; }
    .doc-badge--pending { background: #fef3c7; color: #d97706; }
    .doc-badge--approved { background: #d1fae5; color: #059669; }
    .doc-badge--rejected { background: #fee2e2; color: #dc2626; }
    .doc-badge--resubmit { background: #dbeafe; color: #2563eb; }
    .doc-link { color: #0054cb; text-decoration: none; font-weight: 500; }
    .doc-link:hover { text-decoration: underline; }
</style>

<div class="detail-card">
    <h3>Student Details</h3>
    <div class="detail-row">
        <span class="detail-label">Student ID</span>
        <span class="detail-value"><?php echo e($student['student_id'] ?? $student['id']); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Name</span>
        <span class="detail-value"><?php echo e($student['name']); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value"><?php echo e($student['email']); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Education Level</span>
        <span class="detail-value"><?php echo e($student['education_level'] ?? '-'); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Counselor</span>
        <span class="detail-value">
            <?php if (!empty($student['counselor_name'])): ?>
                <?php echo e($student['counselor_name']); ?>
            <?php else: ?>
                <span style="color:#9ca3af; font-style:italic;">Unassigned</span>
            <?php endif; ?>
        </span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value"><?php echo e($student['status'] ?? 'active'); ?></span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Registered</span>
        <span class="detail-value"><?php echo e($student['created_at'] ?? '-'); ?></span>
    </div>
    <div class="detail-actions">
        <a href="<?php echo url('/admin/students/' . $student['id'] . '/edit'); ?>" class="btn-primary">Edit</a>
        <form method="POST" action="<?php echo url('/admin/students/' . $student['id'] . '/delete'); ?>" onsubmit="return confirm('Are you sure?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-danger">Delete</button>
        </form>
        <a href="<?php echo url('/admin/students'); ?>" class="btn-secondary">Back to List</a>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <h3>Inquiries <span class="section-count">(<?php echo count($inquiries); ?>)</span></h3>
    </div>
    <?php if (!empty($inquiries)): ?>
        <table class="inq-table">
            <thead>
                <tr>
                    <th>Inquiry ID</th>
                    <th>Country</th>
                    <th>Level</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Counselor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquiries as $inq): ?>
                    <tr>
                        <td><a href="<?php echo url('/admin/inquiries/' . $inq['id']); ?>" class="inq-id">#<?php echo e($inq['inquiry_id']); ?></a></td>
                        <td><?php echo e($inq['country_of_interest'] ?? '-'); ?></td>
                        <td><?php echo e($inq['level_of_study'] ?? '-'); ?></td>
                        <td><span class="inq-date"><?php echo e(date('M d, Y', strtotime($inq['created_at']))); ?></span></td>
                        <td><span class="inq-badge inq-badge--<?php echo e($inq['status']); ?>"><?php echo e(ucwords(str_replace('-', ' ', $inq['status']))); ?></span></td>
                        <td><?php echo e($inq['counselor_name'] ?? 'Unassigned'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="inq-empty">No inquiries linked to this student.</div>
    <?php endif; ?>
</div>

<div class="section-card">
    <div class="section-header">
        <h3>Documents <span class="section-count">(<?php echo count($documents); ?>)</span></h3>
        <a href="<?php echo url('/admin/documents/create'); ?>" class="btn-secondary" style="height:32px;padding:0 12px;font-size:13px;">+ Upload</a>
    </div>
    <?php if (!empty($documents)): ?>
        <table class="inq-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><a href="<?php echo url('/admin/documents/' . $doc['id']); ?>" class="doc-link"><?php echo e($doc['name']); ?></a></td>
                        <td><span class="doc-badge doc-badge--<?php echo e($doc['category']); ?>"><?php echo e(ucfirst($doc['category'])); ?></span></td>
                        <td><span class="doc-badge doc-badge--<?php echo e($doc['status']); ?>"><?php echo e(ucfirst($doc['status'])); ?></span></td>
                        <td><span class="inq-date"><?php echo e(date('M d, Y', strtotime($doc['created_at']))); ?></span></td>
                        <td><?php echo e($doc['remarks'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="inq-empty">No documents submitted yet.</div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin-layout.php';