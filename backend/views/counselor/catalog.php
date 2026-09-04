<?php
$colleges = $colleges ?? [];
$courses = $courses ?? [];
$countries = $countries ?? [];
$filters = $filters ?? [];
ob_start();
?>
<style>
.catalog-page{display:flex;flex-direction:column;gap:24px}
.catalog-header h2{margin:0;color:#0b1c30;font-size:32px;font-weight:700;letter-spacing:-0.64px}
.catalog-header p{margin:4px 0 0;color:#73777f;font-size:14px;line-height:1.5}
.catalog-filters{display:flex;gap:12px;align-items:center;flex-wrap:wrap}
.catalog-filters select,.catalog-filters input{height:36px;padding:0 12px;border:1px solid #d0d5dd;border-radius:8px;font-size:13px;color:#344054;background:#fff}
.catalog-filters select:focus,.catalog-filters input:focus{outline:none;border-color:#0054cb}
.catalog-filters button{height:36px;padding:0 16px;border:1px solid #d0d5dd;border-radius:8px;background:#fff;font-size:13px;color:#344054;cursor:pointer}
.catalog-filters button:hover{background:#f9fafb}

.catalog-section{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,0.04)}
.catalog-section__header{padding:20px 24px;border-bottom:1px solid #e5e7eb}
.catalog-section__title{margin:0;font-size:18px;font-weight:700;color:#0b1c30}
.catalog-section__subtitle{margin:2px 0 0;font-size:13px;color:#73777f}

.college-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;padding:20px 24px}
.college-card{border:1px solid #e5e7eb;border-radius:10px;padding:20px;transition:box-shadow 0.15s}
.college-card:hover{box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.college-card__top{display:flex;align-items:center;gap:12px;margin-bottom:14px}
.college-card__avatar{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;flex-shrink:0}
.college-card__avatar--blue{background:#dbeafe;color:#2563eb}
.college-card__avatar--green{background:#d1fae5;color:#059669}
.college-card__avatar--purple{background:#f3e8ff;color:#7c3aed}
.college-card__avatar--orange{background:#fef3c7;color:#d97706}
.college-card__avatar--red{background:#fee2e2;color:#dc2626}
.college-card__name{font-size:16px;font-weight:700;color:#0b1c30}
.college-card__code{font-size:12px;color:#73777f}
.college-card__rows{display:flex;flex-direction:column;gap:8px}
.college-card__row{display:flex;justify-content:space-between;font-size:13px}
.college-card__row span:first-child{color:#73777f}
.college-card__row span:last-child{color:#0b1c30;font-weight:500}
.college-card__link{display:inline-flex;align-items:center;gap:4px;margin-top:12px;font-size:13px;font-weight:500;color:#0054cb;text-decoration:none}
.college-card__link:hover{text-decoration:underline}

.course-table{width:100%;border-collapse:collapse}
.course-table th{padding:12px 20px;text-align:left;font-size:11px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;color:#9ca3af;background:#fafbfc;border-bottom:1px solid #e5e7eb}
.course-table td{padding:14px 20px;border-bottom:1px solid #f3f4f6;font-size:14px;color:#43474f}
.course-table tbody tr:last-child td{border-bottom:none}
.course-table tbody tr:hover{background:#fafbff}
.course-badge{display:inline-flex;padding:2px 10px;border-radius:9999px;font-size:11px;font-weight:500}
.course-badge--bachelor{background:#dbeafe;color:#1d4ed8}
.course-badge--master{background:#e5e7eb;color:#374151}
.course-badge--diploma{background:#f5f3ff;color:#7c3aed}
.course-badge--phd{background:#fee2e2;color:#dc2626}
.course-fee{font-weight:600;color:#0b1c30}
.course-empty{text-align:center;padding:40px 20px;color:#9ca3af;font-size:14px}

.catalog-tabs{display:flex;gap:0;border-bottom:2px solid #e5e7eb}
.catalog-tab{padding:12px 20px;font-size:14px;font-weight:500;color:#73777f;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all 0.15s;background:none;border-top:none;border-left:none;border-right:none}
.catalog-tab:hover{color:#0b1c30}
.catalog-tab--active{color:#0054cb;border-bottom-color:#0054cb}
@media(max-width:768px){.college-grid{grid-template-columns:1fr}}
</style>

<div class="catalog-page">
    <section class="catalog-header">
        <h2>College &amp; Course Catalog</h2>
        <p>Browse partner institutions and available courses to guide your students.</p>
    </section>

    <section class="catalog-filters">
        <form method="GET" action="<?php echo url('/counselor/catalog'); ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
            <input type="text" name="search" placeholder="Search colleges or courses..." value="<?php echo e($filters['search'] ?? ''); ?>">
            <select name="country">
                <option value="">All Countries</option>
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo e($country); ?>" <?php echo ($filters['country'] ?? '') === $country ? 'selected' : ''; ?>><?php echo e($country); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="level">
                <option value="">All Levels</option>
                <option value="bachelor" <?php echo ($filters['level'] ?? '') === 'bachelor' ? 'selected' : ''; ?>>Bachelor</option>
                <option value="master" <?php echo ($filters['level'] ?? '') === 'master' ? 'selected' : ''; ?>>Master</option>
                <option value="diploma" <?php echo ($filters['level'] ?? '') === 'diploma' ? 'selected' : ''; ?>>Diploma</option>
                <option value="phd" <?php echo ($filters['level'] ?? '') === 'phd' ? 'selected' : ''; ?>>PhD</option>
            </select>
            <button type="submit">Search</button>
        </form>
    </section>

    <section class="catalog-section">
        <div class="catalog-tabs">
            <button type="button" class="catalog-tab catalog-tab--active" onclick="showTab('colleges')">Colleges (<?php echo count($colleges); ?>)</button>
            <button type="button" class="catalog-tab" onclick="showTab('courses')">Courses (<?php echo count($courses); ?>)</button>
        </div>

        <div id="tab-colleges">
            <?php if (!empty($colleges)): ?>
                <div class="college-grid">
                    <?php
                    $avatarColors = ['blue','green','purple','orange','red'];
                    $colorIndex = 0;
                    ?>
                    <?php foreach ($colleges as $college): ?>
                        <?php $color = $avatarColors[$colorIndex % count($avatarColors)]; $colorIndex++; ?>
                        <div class="college-card">
                            <div class="college-card__top">
                                <div class="college-card__avatar college-card__avatar--<?php echo $color; ?>">
                                    <?php echo e(substr($college['name'], 0, 2)); ?>
                                </div>
                                <div>
                                    <div class="college-card__name"><?php echo e($college['name']); ?></div>
                                    <div class="college-card__code"><?php echo e($college['code']); ?></div>
                                </div>
                            </div>
                            <div class="college-card__rows">
                                <div class="college-card__row"><span>Country</span><span><?php echo e($college['country']); ?></span></div>
                                <?php if (!empty($college['city'])): ?>
                                    <div class="college-card__row"><span>City</span><span><?php echo e($college['city']); ?></span></div>
                                <?php endif; ?>
                                <?php if (!empty($college['website'])): ?>
                                    <div class="college-card__row"><span>Website</span><a href="<?php echo e($college['website']); ?>" target="_blank" class="college-card__link" style="margin:0;padding:0;">Visit</a></div>
                                <?php endif; ?>
                                <?php if (!empty($college['contact_email'])): ?>
                                    <div class="college-card__row"><span>Contact</span><span><?php echo e($college['contact_email']); ?></span></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="course-empty">No colleges found matching your filters.</div>
            <?php endif; ?>
        </div>

        <div id="tab-courses" style="display:none;">
            <?php if (!empty($courses)): ?>
                <table class="course-table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>College</th>
                            <th>Country</th>
                            <th>Level</th>
                            <th>Duration</th>
                            <th>Tuition Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#0b1c30;"><?php echo e($course['name']); ?></div>
                                    <div style="font-size:12px;color:#73777f;"><?php echo e($course['code']); ?></div>
                                </td>
                                <td><?php echo e($course['college_name'] ?? '-'); ?></td>
                                <td><?php echo e($course['college_country'] ?? '-'); ?></td>
                                <td><span class="course-badge course-badge--<?php echo e($course['level']); ?>"><?php echo e(ucfirst($course['level'])); ?></span></td>
                                <td><?php echo e($course['duration'] ?? '-'); ?></td>
                                <td>
                                    <?php if (!empty($course['tuition_fee'])): ?>
                                        <span class="course-fee"><?php echo e($course['currency'] ?? 'USD'); ?> <?php echo e(number_format((float)$course['tuition_fee'], 0)); ?></span>
                                    <?php else: ?>
                                        <span style="color:#9ca3af;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="course-empty">No courses found matching your filters.</div>
            <?php endif; ?>
        </div>
    </section>
</div>

<script>
function showTab(tab) {
    document.getElementById('tab-colleges').style.display = tab === 'colleges' ? 'block' : 'none';
    document.getElementById('tab-courses').style.display = tab === 'courses' ? 'block' : 'none';
    document.querySelectorAll('.catalog-tab').forEach(function(el) { el.classList.remove('catalog-tab--active'); });
    event.target.classList.add('catalog-tab--active');
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/counselor-layout.php';
