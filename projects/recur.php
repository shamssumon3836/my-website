<!DOCTYPE html>
<html>
<head>
<? include '../head.php'; ?>
<title>Recurrence Relation Solver</title>
<link rel="stylesheet" type="text/css" href="../styles/styles.css">
</head>
<body>
<?include '../nav_bar.php'?>
<div id="content">
<?php
        function get_default($name, $def)
        {
            $x = $_POST[$name];
            if($x == null || !preg_match("/^-?\d+\.?\d*$/", $x))
            {
                $x = $def;
            }
            $GLOBALS[$name] = $x;
        }
        get_default("initx1", 1);
        floatval(get_default("inity1", 1));
        get_default("initx2", 2);
        if ($initx2 - $initx1 != 1)
            $initx2 = $initx1 + 1;
        floatval(get_default("inity2", 1));
        floatval(get_default("recur1", 1));
        floatval(get_default("recur2", 1));
        ?>
    <script>
        function onChangeInitX1()
        {
            var v = document.getElementById("initx1").value;
            var pv = parseInt(v);
            if (isNaN(pv))
            {
                pv = 1;
            }
            if (v != "")
                document.getElementById("initx1").value = pv;
            document.getElementById("initx2").value = pv + 1;
        }
    </script>
    <form method="post" action="recur.php">
        <table>
            <tr>
                <td>f(<input id="initx1" onchange="onChangeInitX1();" onkeydown="onChangeInitX1();" onkeyup="onChangeInitX1();" onkeydown="onChangeInitX1();" onkeypress="onChangeInitX1();" style="width: 10px" type="text" value="<?php echo $initx1;?>" name="initx1" width=1/>)</td>
                <td>=</td>
                <td><input style="width: 40px" type="text" value="<?php echo $inity1;?>" name="inity1"></td>
            </tr>
            <tr>
                <td>f(<input id="initx2" readonly="" style="width: 10px" type="text" value="<?php echo $initx2?>" name="initx2">)</td>
                <td>=</td>
                <td><input style="width: 40px" type="text" value="<?php echo $inity2;?>" name="inity2"></td>
            </tr>
            <tr>
                <td>f(n)</td>
                <td>=</td>
                <td><input style="width: 40px" type="text" value="<?php echo $recur1;?>" name="recur1">*f(n-1)</td>
                <td>+</td>
                <td><input style="width: 40px" type="text" value="<?php echo $recur2;?>" name="recur2">*f(n-2)</td>
            </tr>
        </table>
<br/>
<input type="submit" value="Solve">
    </form>
        <?php
            function array_of_arrays_to_table($A, $headers)
            {
                echo "<table border='1'>";
                echo "<tr>";
                foreach ($headers as $header)
                    echo "<th>$header</th>";
                echo "</tr>";
        $i = 0;
                foreach ($A as $row)
                {
            $i = $i + 1;
            if ($i % 2 == 0)
            {
            $cls = 'even';
            }
           else
                    {
                        $cls = 'odd';
                    }
                    echo "<tr class='$cls'>";
                    foreach ($row as $cell)
                    {
                        echo "<td>$cell</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
            function recur($n, $initx1, $inity1, $initx2, $inity2, $recur1, $recur2)
            {
                $a = array(array($initx1, $inity1), array($initx2, $inity2));
                for ($i=2; $i<$n; $i++)
                {
                    $am1 = $a[$i-1];
                    $am2 = $a[$i-2];
                    $a[$i] = array($i+$initx2-1, null);
                    $a[$i][1] = $recur1 * $a[$i-1][1] + $recur2 * $a[$i-2][1];
                }
                return $a;
            }
            function solve($a, $b, $c)
            {
                $disc = $b * $b - 4 * $a * $c;
                if ($disc > 0)
                {
                    $sdisc = sqrt($disc);
                    return array("real", array((-$b + $sdisc) / (2 * $a), (-$b - $sdisc) / (2 * $a)));
                }
                elseif ($disc == 0)
                {
                    return array("real", array(-$b / (2 * $a)));
                }
                else
                {
                    $disc = -$disc;
                    $sdisc = sqrt($disc);
                    return array("complex", array(array(-$b / (2 * $a), $sdisc / (2 * $a)),
                                                  array(-$b / (2 * $a), -$sdisc / (2 * $a))));
                }
            }
            function solve_linear($a11, $a12, $a21, $a22, $b1, $b2)
            {
                $disc = $a11 * $a22 - $a21 * $a12;
                if ($disc == 0)
                    return array();
                else
                {
                    $inva11 = $a22;
                    $inva22 = $a11;
                    $inva12 = - $a12;
                    $inva21 = - $a21;
                    $sol1 = ($inva11 * $b1 + $inva12 * $b2) / $disc;
                    $sol2 = ($inva21 * $b1 + $inva22 * $b2) / $disc;
                    return array($sol1, $sol2);
                }
            }
            function get_equation($recur1, $recur2, $initx1, $inity1, $initx2, $inity2)
            {
                if ($recur1 == 0 && $recur2 == 0)
                {
                    return "$inity1*(n==$initx1) + $inity2*(n==$initx2)";
                }
                if ($recur1 == 0)
                {
                    return "$inity2*($recur2)^((n-$initx2)/2)*((n-$initx2)%2==0) + $inity1*($recur2)^((n-$initx1)/2)*((n-$initx1)%2==0)";
                }
                if ($recur2 == 0)
                {
                    if ($inity2 == $inity1 * $recur1)
                    {
                        return "$inity1*($recur1)^(n-$initx1)";
                    }
                    else
                    {
                        return "$inity2*($recur1)^(n-$initx2)*(n>=$initx2) + $inity1*(n==$initx1)";
                    }
                }
                $roots = solve(1, -$recur1, -$recur2);
                $err = "Error";
                if ($roots[0] == "real")
                {
                    $roots = $roots[1];
                    if (count($roots) == 2)
                    {
                        $coeffs = solve_linear(pow($roots[0], $initx1),
                                               pow($roots[1], $initx1),
                                               pow($roots[0], $initx2),
                                               pow($roots[1], $initx2),
                                               $inity1,
                                               $inity2);
                        if (count($coeffs) == 0)
                            return $err;
                        return "$coeffs[0]*($roots[0])^n + $coeffs[1]*($roots[1])^n";
                    }
                    elseif (count($roots) == 1)
                    {
                        $coeffs = solve_linear(pow($roots[0], $initx1),
                                               $initx1 * pow($roots[0], $initx1),
                                               pow($roots[0], $initx2),
                                               $initx2 * pow($roots[0], $initx2),
                                               $inity1,
                                               $inity2);
                        if (count($coeffs) == 0)
                            return $err;
                        return "$coeffs[0]*($roots[0])^n + $coeffs[1]*n*($roots[0])^n";
                    }
                    else
                    {
                        return $err;
                    }
                }
                else
                {
                    $roots = $roots[1];
                    $a1 = $inity1;
                    $a2 = $inity2;
                    $alpha = $roots[0][0];
                    $beta = $roots[0][1];
                    $A = 2 * $alpha;
                    $B = - ($alpha * $alpha + $beta * $beta);
                    $E = (-$A * $a1 + $a2) / $B;

                    $num = $A * $A * $a1 - $A * $a2 + 2 * $a1 * $B;
                    $disc = - ($A * $A + 4 * $B);
                    $den = ($B * sqrt($disc));
                    $F = $num / $den;
                    $theta = acos($A / (2 * sqrt(-$B)));
                    $mB = -$B;
                    return "($mB)^(n/2)*($E*cos($theta*n)+$F*sin($theta*n))";
                }

            }
            $n = 10;
            $equation = get_equation($recur1, $recur2, $initx1, $inity1, $initx2, $inity2);
            echo "<p>Solution: f(n)=$equation</p>";
            array_of_arrays_to_table(recur($n, $initx1, $inity1, $initx2, $inity2, $recur1, $recur2), array("n", "f(n)"));
        ?>
</div>
</body>
</html>
