<table border="1">
    <thead>
        <tr>
            <?php foreach (array_keys($products[0]) as $key): ?>
                <th><?= htmlspecialchars($key) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <?php foreach ($product as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
