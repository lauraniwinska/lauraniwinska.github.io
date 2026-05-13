<?php
//inicjalizacja
session_start();
require_once __DIR__ . '/../autoload.php';
use App\Serializer;

// automatyczne czyszczenie ciasteczek
if (isset($_GET['clear'])) {
    setcookie('input_data', '', time() - 3600, '/');
    setcookie('input_format', '', time() - 3600, '/');
    setcookie('output_format', '', time() - 3600, '/');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// ustawiamy domyslne wartosci
$inputData = '';
$inputFormat = 'CSV';
$outputFormat = 'JSON';
$outputData = '';

//wczytujemy wartości z ciastek
if (isset($_COOKIE['input_data'])) {
    $inputData = $_COOKIE['input_data'];
}
if (isset($_COOKIE['input_format'])) {
    $inputFormat = $_COOKIE['input_format'];
}
if (isset($_COOKIE['output_format'])) {
    $outputFormat = $_COOKIE['output_format'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = $_POST['input'] ?? '';
    $inputFormat = $_POST['inputFormat'] ?? 'CSV';
    $outputFormat = $_POST['outputFormat'] ?? 'JSON';

    //zapisujemy w ciastkach
    setcookie('input_data', $inputData, time() + 30 * 24 * 60 * 60, '/');
    setcookie('input_format', $inputFormat, time() + 30 * 24 * 60 * 60, '/');
    setcookie('output_format', $outputFormat, time() + 30 * 24 * 60 * 60, '/');

    //konwertujemy dane
    $serializer = new Serializer();
    $outputData = $serializer->convert($inputData, $inputFormat, $outputFormat) ?? 'Błąd konwersji!';
}

$formats = ['CSV', 'SSV', 'TSV', 'JSON', 'YAML'];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konwerter Danych</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f1b2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: #ffffd5;
            box-shadow: 5px 5px #ffffff;
            padding: 40px;
            border: #9a9a76 solid 3px;
            max-width: 900px;
            width: 100%;
        }
        h1 {
            font-family: "Times New Roman", Times, serif;
            text-align: center;
            font-weight: bold;
            font-size: 50px;
            color: #9a9a76;
            margin-bottom: 30px;
            text-shadow: 1px 1px #000000, -1px 1px #ffffff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-family: "Lucida Console", "Courier New", monospace;
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        textarea, select, button {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            font-size: 1rem;
            font-family: "Lucida Console", "Courier New", monospace;
        }
        textarea {
            resize: vertical;
            min-height: 300px;
            background: #f9f9f9;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .textarea-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .textarea-row .form-group {
            margin-bottom: 0;
        }
        button {
            background-color: #9a9a76;
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
            padding: 15px 12px;
            font-size: 1.1rem;
            box-shadow: 1px 1px #000000;
        }

        button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> Konwerter Danych</h1>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="inputFormat">Format wejściowy:</label>
                    <select name="inputFormat" id="inputFormat">
                        <?php foreach ($formats as $format): ?>
                            <option value="<?php echo $format; ?>" <?php echo $inputFormat === $format ? 'selected' : ''; ?>>
                                <?php echo $format; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="outputFormat">Format wyjściowy:</label>
                    <select name="outputFormat" id="outputFormat">
                        <?php foreach ($formats as $format): ?>
                            <option value="<?php echo $format; ?>" <?php echo $outputFormat === $format ? 'selected' : ''; ?>>
                                <?php echo $format; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="textarea-row">
                <div class="form-group">
                    <textarea name="input" id="input" placeholder="Dane początkowe..."><?php echo htmlspecialchars($inputData); ?></textarea>
                </div>

                <div class="form-group">
                    <textarea id="output" readonly placeholder="Wynik..."><?php echo htmlspecialchars($outputData); ?></textarea>
                </div>
            </div>

            <button type="submit"> Convert </button>
        </form>
    </div>
</body>
</html>

