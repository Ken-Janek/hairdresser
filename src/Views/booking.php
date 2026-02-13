<header>
  <h1>Book an Appointment</h1>
  <p>Select a stylist and time.</p>
</header>

<?php if (!empty($error)) : ?>
  <div class="alert"><?= $escape($error) ?></div>
<?php endif; ?>

<section class="panel">
  <h2>Choose Date & Stylist</h2>
  <form method="GET" action="/">
    <label>
      Date
      <input type="date" name="date" value="<?= $escape($date) ?>" required />
    </label>
    <label>
      Stylist
      <select name="stylist_id" required>
        <?php foreach ($stylists as $stylist) : ?>
          <option value="<?= (int)$stylist['id'] ?>" <?= (int)$stylist['id'] === (int)$stylistId ? 'selected' : '' ?>>
            <?= $escape($stylist['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>
      Service
      <select name="service_id" required>
        <?php foreach ($services as $service) : ?>
          <option value="<?= (int)$service['id'] ?>" <?= (int)$service['id'] === (int)$serviceId ? 'selected' : '' ?>>
            <?= $escape($service['name']) ?> - $<?= number_format($service['price_cents'] / 100, 2) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>
    <button type="submit">Update Slots</button>
  </form>
</section>

<section class="panel">
  <h2>Book</h2>
  <form method="POST" action="/book">
    <input type="hidden" name="date" value="<?= $escape($date) ?>" />
    <input type="hidden" name="stylist_id" value="<?= (int)$stylistId ?>" />
    <input type="hidden" name="service_id" value="<?= (int)$serviceId ?>" />

    <label>
      Time
      <select name="time" required>
        <?php if (empty($slots)) : ?>
          <option value="">No slots available</option>
        <?php else : ?>
          <?php foreach ($slots as $slot) : ?>
            <option value="<?= $escape($slot) ?>"><?= $escape($slot) ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </label>

    <label>
      Name
      <input type="text" name="customer_name" required />
    </label>
    <label>
      Email
      <input type="email" name="email" required />
    </label>
    <label>
      Phone (optional)
      <input type="text" name="phone" />
    </label>

    <button type="submit">Confirm Booking</button>
  </form>
</section>

<footer>
  <a href="/admin">Admin view</a>
</footer>
