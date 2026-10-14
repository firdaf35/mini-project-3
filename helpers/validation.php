<?php
// helpers/validation.php
function validateNonNegative($value, $fieldName) {
    if ($value < 0) {
        return "$fieldName tidak boleh bernilai negatif.";
    }
    return null;
}