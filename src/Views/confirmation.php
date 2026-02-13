<header>
  <h1>Booking Confirmed</h1>
  <p>Thanks, <?= $escape($booking['customer_name']) ?>!</p>
</header>

<section class="panel">
  <p><strong>Stylist:</strong> <?= $escape($booking['stylist_name']) ?></p>
  <p><strong>Service:</strong> <?= $escape($booking['service_name']) ?></p>
  <p><strong>Date:</strong> <?= $escape($booking['date']) ?></p>
  <p><strong>Time:</strong> <?= $escape(substr($booking['start_time'], 0, 5)) ?> - <?= $escape(substr($booking['end_time'], 0, 5)) ?></p>
  <p><strong>Email:</strong> <?= $escape($booking['email']) ?></p>
  <?php if (!empty($booking['phone'])) : ?>
    <p><strong>Phone:</strong> <?= $escape($booking['phone']) ?></p>
  <?php endif; ?>
</section>

<footer>
  <a href="/">Back to booking</a>
</footer>
