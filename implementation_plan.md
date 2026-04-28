# OlehLampung E-Commerce Implementation Plan

## Overview

Build a full-featured e-commerce platform for **OlehLampung** (oleh-oleh khas Lampung) using the existing **Laravel 12 + Tailwind CSS 4 + Vite** stack. The design follows the mockups provided, featuring a **dark navy header** with **amber/gold accents** on a **white/cream background**.

## Color Palette (from mockups)

| Token | Hex | Usage |
|---|---|---|
| `--color-navy` | `#1B1F2A` | Header, footer, dark backgrounds |
| `--color-navy-light` | `#2D3244` | Hover states on dark bg |
| `--color-amber` | `#F59E0B` | Primary CTA buttons, active states |
| `--color-amber-dark` | `#D97706` | Button hover |
| `--color-amber-light` | `#FEF3C7` | Soft accent backgrounds, tags |
| `--color-cream` | `#FFFBEB` | Section backgrounds |
| `--color-green` | `#22C55E` | Success states, badges |
| `--color-red` | `#EF4444` | Sale badges, error states |
| `--color-gray-50` | `#F9FAFB` | Alternate section bg |
| `--color-gray-200` | `#E5E7EB` | Borders, dividers |
| `--color-gray-500` | `#6B7280` | Muted text |
| `--color-gray-900` | `#111827` | Body text |

## Pages to Build

1. **Beranda** — Hero, kategori unggulan, produk pilihan
2. **Pencarian** — Search bar, sidebar filter, product grid, pagination
3. **Katalog** — Category-based product listing (reuses search layout)
4. **Detail Produk** — Image gallery, description tabs, related products
5. **Keranjang** — Cart items, quantity controls, subtotal
6. **Pembayaran & Checkout** — Shipping form, shipping method, payment method, order summary
7. **Riwayat Pesanan** — Order history list + order detail/tracking
8. **Admin Panel** — Product CRUD management (table + form)

---

## User Review Required

> [!IMPORTANT]
> **Frontend-only vs Full-stack?** The mockups show a complete e-commerce flow. I plan to build this **full-stack** with:
> - Database tables (products, categories, orders, order_items, carts)
> - Eloquent Models & Controllers
> - Working cart (session-based, no auth required for browsing)
> - Admin panel for product management
> 
> If you prefer **frontend-only** (static Blade templates with dummy data), please let me know.

> [!IMPORTANT]
> **Authentication:** The mockups don't show login/register pages. I plan to:
> - Use **Laravel Breeze** for basic auth (admin login only)
> - Guest checkout (no account needed to buy)
> - Admin routes protected by auth middleware
>
> Is this acceptable?

## Open Questions

1. **Database:** The project uses SQLite (`database.sqlite`). Should I continue with SQLite or switch to MySQL?
2. **Payment gateway:** Should the payment page be functional (integrate with Midtrans/Xendit) or just UI mockup for now?
3. **Product images:** Should I generate placeholder product images, or will you provide them?
4. **Admin scope:** Simple CRUD for products only, or also manage orders/categories/users?

---

## Proposed Changes

### Phase 1: Foundation (Database, Models, Seeders)

#### [NEW] database/migrations/xxxx_create_categories_table.php
- `id`, `name`, `slug`, `image`, `description`, `timestamps`

#### [NEW] database/migrations/xxxx_create_products_table.php
- `id`, `category_id` (FK), `name`, `slug`, `description`, `short_description`, `price`, `original_price` (for discount display), `stock`, `weight`, `rating`, `review_count`, `images` (JSON), `is_featured`, `is_sale`, `timestamps`

#### [NEW] database/migrations/xxxx_create_orders_table.php
- `id`, `order_number`, `first_name`, `last_name`, `whatsapp`, `email`, `address`, `city`, `province`, `postal_code`, `country`, `shipping_method`, `shipping_cost`, `payment_method`, `subtotal`, `discount`, `total`, `status`, `notes`, `timestamps`

#### [NEW] database/migrations/xxxx_create_order_items_table.php
- `id`, `order_id` (FK), `product_id` (FK), `quantity`, `price`, `timestamps`

#### [NEW] app/Models/Category.php
#### [NEW] app/Models/Product.php
#### [NEW] app/Models/Order.php
#### [NEW] app/Models/OrderItem.php

#### [NEW] database/seeders/CategorySeeder.php
- Seed: Kopi, Makanan, Kerajinan, Minuman, Souvenir

#### [NEW] database/seeders/ProductSeeder.php
- Seed ~15–20 products with realistic Lampung oleh-oleh data (Keripik Pisang, Kopi Robusta, Kain Tapis, Lempok Durian, Sambol Lampung, etc.)

---

### Phase 2: Design System & Layout

#### [MODIFY] [app.css](file:///c:/laragon/www/olehlampung/resources/css/app.css)
- Define custom color tokens via `@theme`
- Add custom component classes (buttons, cards, badges, form inputs)
- Typography: Google Fonts `Inter` + `Outfit` for headings

#### [NEW] resources/views/layouts/app.blade.php
- Main layout with:
  - Dark navy header/navbar (logo, nav links: Beranda/Kopi/Makanan/Kerajinan/Tentang Kami)
  - Search icon, cart icon with badge
  - Mobile hamburger menu
  - Footer with 4 columns (Tentang Kami, Produk, Dukungan, Ikuti Kami)
  - Copyright bar

#### [NEW] resources/views/layouts/admin.blade.php
- Admin sidebar layout

#### [NEW] resources/views/components/ (Blade Components)
- `product-card.blade.php` — Reusable product card (image, title, price, rating, add-to-cart)
- `category-card.blade.php` — Category card with overlay text
- `breadcrumb.blade.php` — Breadcrumb navigation
- `pagination.blade.php` — Custom pagination
- `price-tag.blade.php` — Formatted Rupiah price display
- `star-rating.blade.php` — Star rating display

---

### Phase 3: Page Views & Controllers

#### Beranda (Home)
- **[NEW] app/Http/Controllers/HomeController.php**
  - Fetch featured categories, featured products
- **[MODIFY] [welcome.blade.php](file:///c:/laragon/www/olehlampung/resources/views/welcome.blade.php)** → rename/replace with `home.blade.php`
- **[NEW] resources/views/home.blade.php**
  - Hero section (CTA + Siger Lampung image)
  - Kategori Unggulan (3 category cards)
  - Produk Pilihan (4 product cards)

#### Pencarian & Katalog
- **[NEW] app/Http/Controllers/ProductController.php**
  - `index()` — catalog listing with filters
  - `search()` — search with query param
  - `show()` — product detail
- **[NEW] resources/views/products/index.blade.php** (Pencarian/Katalog)
  - Search bar with result count
  - Active filter tags
  - Left sidebar: category checkboxes, price range slider, rating filter
  - Product grid (3 columns)
  - Pagination
- **[NEW] resources/views/products/show.blade.php** (Detail Produk)
  - Image gallery (main + thumbnails)
  - Product info: name, rating, price, description
  - Tabs: Deskripsi, Informasi Tambahan, Ulasan
  - Quantity selector + Add to Cart button
  - Related products section

#### Keranjang (Cart)
- **[NEW] app/Http/Controllers/CartController.php**
  - Session-based cart (add, update, remove, clear)
- **[NEW] resources/views/cart/index.blade.php**
  - Cart item table (image, name, price, quantity, subtotal)
  - Quantity +/- controls
  - Remove button
  - Cart summary (subtotal, shipping estimate, total)
  - Proceed to checkout button

#### Pembayaran & Checkout
- **[NEW] app/Http/Controllers/CheckoutController.php**
  - `index()` — checkout form
  - `store()` — process order
  - `success()` — payment success page
- **[NEW] resources/views/checkout/index.blade.php**
  - Step indicator (Keranjang → Checkout → Konfirmasi)
  - Left: Shipping form (name, whatsapp, email, address, city, province, postal code)
  - Shipping method selector (JNE Reguler, JNE YES, J&T, Kurir Lokal)
  - Payment method selector (Transfer Bank, QRIS, GoPay, OVO, DANA, COD)
  - Right: Order summary (items, voucher, subtotal, shipping, discount, total)
  - Bayar Sekarang button
- **[NEW] resources/views/checkout/success.blade.php**
  - Success icon + "Pembayaran Berhasil!"
  - Order details (date, payment method, shipping, status, total)
  - Ordered items list
  - Order tracking timeline
  - Lacak Pesanan button + Bagikan ke WhatsApp
  - Back to home link

#### Riwayat Pesanan
- **[NEW] app/Http/Controllers/OrderController.php**
  - `index()` — order history list
  - `show()` — order detail
- **[NEW] resources/views/orders/index.blade.php**
  - Order list with status badges
- **[NEW] resources/views/orders/show.blade.php**
  - Reuse success page layout for order detail

#### Admin Panel
- **[NEW] app/Http/Controllers/Admin/ProductController.php**
  - Full CRUD for products
- **[NEW] resources/views/admin/products/index.blade.php**
  - Product table with search, filter, pagination
  - Action buttons (edit, delete)
- **[NEW] resources/views/admin/products/create.blade.php**
  - Product form (name, category, price, description, images, stock, etc.)
- **[NEW] resources/views/admin/products/edit.blade.php**
  - Edit form (reuses create form partial)

---

### Phase 4: Routes

#### [MODIFY] [web.php](file:///c:/laragon/www/olehlampung/routes/web.php)

```php
// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cari', [ProductController::class, 'search'])->name('products.search');
Route::get('/kategori/{slug}', [ProductController::class, 'category'])->name('products.category');

// Cart
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/berhasil/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Orders
Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');

// Admin (protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::resource('products', Admin\ProductController::class);
});
```

---

### Phase 5: Frontend Assets & JavaScript

#### [MODIFY] [app.js](file:///c:/laragon/www/olehlampung/resources/js/app.js)
- Mobile menu toggle
- Cart quantity controls (AJAX)
- Add to cart (AJAX with toast notification)
- Image gallery for product detail
- Price range slider
- Filter toggle on mobile
- Smooth scroll animations

---

## Implementation Order

| Phase | Description | Est. Files |
|-------|-------------|-----------|
| 1 | Database schema, models, seeders | ~10 files |
| 2 | Design system, layouts, components | ~10 files |
| 3 | Pages: Home, Catalog, Detail, Cart, Checkout, Orders, Admin | ~15 files |
| 4 | Routes & controllers | ~6 files |
| 5 | JavaScript interactivity | ~1 file |
| **Total** | | **~42 files** |

## Verification Plan

### Automated Tests
- `php artisan migrate:fresh --seed` — verify migrations and seeders run cleanly
- `npm run build` — verify Vite compiles without errors
- Open each page in browser to visually verify layout matches mockups

### Manual Verification
- Navigate through all pages in browser via `php artisan serve` + `npm run dev`
- Test full flow: browse → search → product detail → add to cart → checkout → success
- Test admin panel: create, edit, delete products
- Test responsive design at mobile/tablet/desktop breakpoints
- Record browser walkthrough video
