<header>
  <h1>Admin Bookings</h1>
  <p>Filter by date and manage appointments.</p>
</header>

<section class="panel">
  <form method="GET" action="/admin">
    <label>
      Date
      <input type="date" name="date" value="<?= $escape($date) ?>" required />
    </label>
    <button type="submit">Filter</button>
  </form>
</section>

<section class="panel">
  <?php if (empty($bookings)) : ?>
    <p>No bookings for this date.</p>
  <?php else : ?>
    <table>
      <thead>
        <tr>
          <th>Time</th>
          <th>Client</th>
          <th>Stylist</th>
          <th>Service</th>
          <th>Contact</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $booking) : ?>
          <tr>
            <td><?= $escape(substr($booking['start_time'], 0, 5)) ?></td>
            <td><?= $escape($booking['customer_name']) ?></td>
            <td><?= $escape($booking['stylist_name']) ?></td>
            <td><?= $escape($booking['service_name']) ?></td>
            <td>
              <div><?= $escape($booking['email']) ?></div>
              <?php if (!empty($booking['phone'])) : ?>
                <div><?= $escape($booking['phone']) ?></div>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" action="/admin/cancel">
                <input type="hidden" name="id" value="<?= (int)$booking['id'] ?>" />
                <input type="hidden" name="date" value="<?= $escape($date) ?>" />
                <button type="submit" class="danger">Cancel</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<footer>
  <a href="/">Back to booking</a>
</footer>
