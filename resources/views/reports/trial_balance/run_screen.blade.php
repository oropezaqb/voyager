<body>
    <h6 style='text-align:center;'>{{ auth()->user()->current_company->company->name }}</h6>
    <h5 style='font-weight: bold;text-align:center;'>{{ $query->title }}</h5>
    <p style='text-align:center;'>{{ $query->date }}</p>
    <div class="run">
        <table border=0 cellpadding=5 cellspacing=0 align=center style='border-collapse: collapse; border: 0px solid rgb(192, 192, 192);'>
            <tr>
                <th style='text-align: left;'>Account Title</th>
                <th style='text-align: right;'>Debit</th>
                <th style='text-align: right;'>Credit</th>
            </tr>
            <?php
                $total_debit = 0;
                $total_credit = 0;
                while ($row = $stmt->fetch()) {
                    echo '<tr>';
                    echo "<td style='min-width:200px'>$row[title]</td>";
                    if ($row['debit'] > 0)
                    {
                        echo "<td style='text-align:right; min-width:100px'>" . +$row['debit'] . "</td>";
                        echo "<td style='text-align:right; min-width:100px'></td>";
                        $total_debit += +$row['debit'];
                    }
                    else
                    {
                        echo "<td style='text-align:right; min-width:100px'></td>";
                        echo "<td style='text-align:right; min-width:100px'>" . -$row['debit'] . "</td>";
                        $total_credit += -$row['debit'];
                    }
                    echo '</tr>';
                }
            ?>
            <tr><td>Total<td style='text-align:right; border-bottom-style: double; border-top-style: solid; border-top-width:1px;'>{{ $total_debit }}<td style='text-align:right; border-bottom-style: double; border-top-style: solid; border-top-width:1px;'>{{ $total_credit }}
        </table>
    </div>
</body>
