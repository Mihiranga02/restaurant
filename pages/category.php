<?php
/**
 * category.php — Single dynamic category page
 *
 * URL: pages/category.php?category=<slug>
 *
 * The page first tries to load category data from the MySQL database
 * (when DB credentials are configured in includes/config.php).
 * If the database is unavailable it transparently falls back to the
 * $menuData array defined in includes/config.php.
 */

require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';

$basePath = '../';

// ── 1. Validate the ?category= parameter ──────────────────────────────────
$slug = isset($_GET['category']) ? trim($_GET['category']) : '';

// Allowlist: only slugs that exist in our static data are valid.
$validSlugs = array_keys($menuData);
if ($slug === '' || !in_array($slug, $validSlugs, true)) {
    http_response_code(404);
    $pageTitle = SITE_NAME . ' - Category Not Found';
    $menu      = null;
} else {
    $menu = null; // will be populated below

    // ── 2. Try the database first ─────────────────────────────────────────
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare(
                'SELECT c.title, mi.name, mi.price, mi.image, p.id AS product_id
                 FROM categories c
                 JOIN menu_items mi ON mi.category_id = c.id
                 LEFT JOIN products p ON p.name = mi.name
                 WHERE c.slug = :slug
                 ORDER BY mi.id'
            );
            $stmt->execute([':slug' => $slug]);
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
                $menu = [
                    'title' => $rows[0]['title'],
                    'items' => array_map(fn($r) => [
                        'name'       => $r['name'],
                        'price'      => $r['price'],
                        'image'      => $r['image'],
                        'product_id' => $r['product_id'],
                    ], $rows),
                ];
            }
        } catch (PDOException $e) {
            // DB query failed — fall through to static data.
            $menu = null;
        }
    }

    // ── 3. Fall back to $menuData when DB is unavailable or returned nothing
    if ($menu === null) {
        $menu = $menuData[$slug];
    }

    $pageTitle = SITE_NAME . '-' . ucwords(str_replace('-', ' ', $slug));
}

include '../includes/header.php';
?>

<!-- catogary menu -->
<div class="menu" id="Menu">

<?php if ($menu === null): ?>
    <h1>Category Not Found</h1>
    <p style="text-align:center; padding:2rem;">
        The requested category does not exist.
        <a href="<?php echo $basePath; ?>index.php#category">Back to all categories</a>
    </p>
<?php else: ?>
    <h1><?php echo htmlspecialchars($menu['title']); ?></h1>

    <div class="menu_box">
    <?php foreach ($menu['items'] as $item): ?>
        <div class="menu_card">
            <div class="menu_image">
                <img src="<?php echo $basePath . htmlspecialchars($item['image']); ?>"
                     alt="<?php echo htmlspecialchars($item['name']); ?>">
            </div>
            <h2><?php echo htmlspecialchars($item['name']); ?></h2>
            <h3><?php echo htmlspecialchars($item['price']); ?></h3>
            <?php if (!empty($item['product_id'])): ?>
                <form method="POST" action="<?php echo $basePath; ?>cart.php" style="margin-top:8px;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo (int)$item['product_id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="redirect" value="pages/category.php?category=<?php echo urlencode($slug); ?>">
                    <button type="submit" class="menu_btn" style="background:#fac031;color:#000;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-weight:700;width:100%;">Add to Cart</button>
                </form>
            <?php else: ?>
                <a href="<?php echo $basePath; ?>index.php#category" class="menu_btn" style="display:inline-block;background:#555;color:#ccc;padding:8px 18px;border-radius:6px;text-decoration:none;font-weight:700;">View Menu</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>

<?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
