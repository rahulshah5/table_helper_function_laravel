<?php

if (!function_exists('table')) {
    function table(...$vars): void
    {
        // Modern styling with clean UI
        echo "<div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Oxygen, Ubuntu, sans-serif;
                          background: #fff;
                          border-radius: 8px;
                          box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                          margin: 20px 0;
                          overflow: hidden;'>";

        // Handle paginated results, collections and single items
        $data = $vars[0];
        $isPaginated = $data instanceof \Illuminate\Pagination\LengthAwarePaginator;

        // Extract items from pagination if needed
        $items = $isPaginated ? $data->items() : ($data instanceof \Illuminate\Support\Collection ?
            $data : (is_array($data) ? collect($data) : collect([$data])));

        // Get first item to determine columns
        $firstItem = $items[0] ?? null;
        if (!$firstItem) {
            echo "<div style='padding: 16px; color: #666; text-align: center;'>No data to display</div>";
            echo "</div>";
            die();
        }

        // Convert to array if it's an object
        $firstItemArray = is_object($firstItem) ? $firstItem->toArray() : $firstItem;

        // Add search and improved column visibility controls
        echo "<div style='padding: 16px 16px 8px; display: flex; flex-direction: column; gap: 12px;'>";

        // Search box
        echo "<div style='display: flex; justify-content: flex-end;'>";
        echo "<div style='position: relative; width: 300px;'>";
        echo "<input type='text' id='table-search' placeholder='Search...' style='width: 100%; padding: 8px 12px 8px 32px; border: 1px solid #dfe1e6; border-radius: 4px; font-size: 14px; outline: none;'>";
        echo "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='16' height='16' style='position: absolute; left: 10px; top: 50%; transform: translateY(-50%); fill: #5e6c84;'><path d='M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z'/></svg>";
        echo "</div>";
        echo "</div>";

        // Column visibility toggle buttons
        echo "<div style='display: flex; flex-wrap: wrap; gap: 8px; align-items: center;margin-bottom:18px'>";
        echo "<span style='font-size: 13px; color: #5e6c84; margin-right: 4px;'>Toggle columns:</span>";

        $columnIndex = 0;
        foreach (array_keys($firstItemArray) as $column) {
            $columnId = 'col-toggle-' . $columnIndex;
            echo "<div class='column-toggle-btn' data-column='{$columnIndex}' style='display: inline-flex; align-items: center; padding: 4px 10px; background: #0052cc; color: white; border-radius: 6px; font-size: 12px; cursor: pointer; user-select: none;'>";
            echo "<span>" . htmlspecialchars($column) . "</span>";
            echo "<input type='checkbox' id='{$columnId}' class='column-toggle' data-column='{$columnIndex}' checked style='display:none;'>";
            echo "</div>";
            $columnIndex++;
        }

        echo "</div>";
        echo "</div>";

        // Create table structure with modern styling
        echo "<table id='data-table' style='width: 100%; border-collapse: collapse; font-size: 14px;'>";

        // Table headers with modern styling
        echo "<thead>";
        echo "<tr style='background: #f7f9fc; border-bottom: 1px solid #eaedf3;'>";
        foreach (array_keys($firstItemArray) as $column) {
            echo "<th style='padding: 12px 16px; text-align: left; color: #5e6c84; font-weight: 600; text-transform: uppercase; font-size: 12px;'>"
                . htmlspecialchars($column) . "</th>";
        }
        echo "</tr>";
        echo "</thead>";

        // Table body - this section was missing
        echo "<tbody>";
        $rowCount = 0;
        foreach ($items as $item) {
            $rowStyle = $rowCount % 2 === 0 ? 'background: #fff;' : 'background: #f9fafc;';
            echo "<tr class='data-row' style='{$rowStyle} border-bottom: 1px solid #eaedf3;'>";
            $itemArray = is_object($item) ? $item->toArray() : $item;
            foreach ($itemArray as $value) {
                echo "<td style='padding: 12px 16px; color: #172b4d;'>";
                if (is_array($value) || is_object($value)) {
                    echo "<details style='cursor: pointer;'>";
                    echo "<summary style='color: #0052cc; font-weight: 500;'>View Details</summary>";
                    echo "<div style='margin-top: 8px; padding: 8px; background: #f7f9fc; border-radius: 4px; font-family: monospace; font-size: 12px; white-space: pre-wrap;'>"
                        . htmlspecialchars(print_r($value, true)) . "</div>";
                    echo "</details>";
                } else {
                    echo htmlspecialchars($value);
                }
                echo "</td>";
            }
            echo "</tr>";
            $rowCount++;
        }
        echo "</tbody>";
        echo "</table>";

        // Display pagination info if available
        if ($isPaginated) {
            echo "<div style='padding: 12px 16px; background: #f7f9fc; border-top: 1px solid #eaedf3; display: flex; justify-content: space-between; align-items: center;'>";
            echo "<div style='color: #5e6c84; font-size: 13px;'>Showing {$data->firstItem()} to {$data->lastItem()} of {$data->total()} entries</div>";
            echo "<div style='display: flex; gap: 8px;'>";

            // Previous page button
            $prevUrl = $data->previousPageUrl();
            $prevClass = $data->onFirstPage() ? 'opacity: 0.5; cursor: not-allowed;' : 'cursor: pointer;';
            echo "<a href='{$prevUrl}' style='padding: 6px 12px; background: #fff; border: 1px solid #dfe1e6; border-radius: 3px; color: #0052cc; font-size: 13px; {$prevClass}; text-decoration: none;'>Previous</a>";

            // Page numbers
            $currentPage = $data->currentPage();
            $lastPage = $data->lastPage();

            for ($i = max(1, $currentPage - 2); $i <= min($lastPage, $currentPage + 2); $i++) {
                $activeStyle = $i === $currentPage ? 'background: #0052cc; color: #fff; border-color: #0052cc;' : '';
                $pageUrl = $data->url($i);
                echo "<a href='{$pageUrl}' style='padding: 6px 12px; background: #fff; border: 1px solid #dfe1e6; border-radius: 3px; color: #0052cc; font-size: 13px; {$activeStyle}; text-decoration: none;'>{$i}</a>";
            }

            // Next page button
            $nextUrl = $data->nextPageUrl();
            $nextClass = $data->hasMorePages() ? 'cursor: pointer;' : 'opacity: 0.5; cursor: not-allowed;';
            echo "<a href='{$nextUrl}' style='padding: 6px 12px; background: #fff; border: 1px solid #dfe1e6; border-radius: 3px; color: #0052cc; font-size: 13px; {$nextClass}; text-decoration: none;'>Next</a>";

            echo "</div>";
            echo "</div>";
        }

        // Add JavaScript for search and improved column visibility functionality
        echo "<script>
            // Search functionality
            document.getElementById('table-search').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#data-table tbody tr.data-row');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if(text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Column visibility toggle buttons
            document.querySelectorAll('.column-toggle-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const columnIndex = parseInt(this.getAttribute('data-column'));
                    const checkbox = this.querySelector('input[type=checkbox]');
                    checkbox.checked = !checkbox.checked;

                    // Update button appearance
                    if (checkbox.checked) {
                        this.style.background = '#0052cc';
                        this.style.color = 'white';
                    } else {
                        this.style.background = '#f4f5f7';
                        this.style.color = '#42526e';
                    }

                    // Toggle column visibility
                    const table = document.getElementById('data-table');
                    const headers = table.querySelectorAll('thead th');
                    const rows = table.querySelectorAll('tbody tr');

                    // Toggle header visibility
                    if (headers[columnIndex]) {
                        headers[columnIndex].style.display = checkbox.checked ? '' : 'none';
                    }

                    // Toggle cell visibility in each row
                    rows.forEach(row => {
                        const cells = row.querySelectorAll('td');
                        if (cells[columnIndex]) {
                            cells[columnIndex].style.display = checkbox.checked ? '' : 'none';
                        }
                    });
                });
            });
        </script>";

        echo "</div>";
        die();
    }
}
