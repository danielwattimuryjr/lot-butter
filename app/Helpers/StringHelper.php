<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Convert string to snake_case
     */
    public static function toSnakeCase(string $string): string
    {
        // Replace spaces and special characters with underscores
        $string = preg_replace('/[^\w]+/', '_', $string);

        // Insert underscores before uppercase letters and convert to lowercase
        $string = preg_replace('/([a-z])([A-Z])/', '$1_$2', $string);

        return strtolower(trim($string, '_'));
    }

    /**
     * Convert string to camelCase
     */
    public static function toCamelCase(string $string): string
    {
        // Convert to title case and remove spaces
        $string = str_replace(['-', '_', ' '], '', ucwords($string, '-_ '));

        // Make first character lowercase
        return lcfirst($string);
    }

    /**
     * Convert string to PascalCase
     */
    public static function toPascalCase(string $string): string
    {
        // Convert to camelCase then uppercase first character
        return ucfirst(self::toCamelCase($string));
    }

    /**
     * Convert string to kebab-case
     */
    public static function toKebabCase(string $string): string
    {
        // Similar to snake_case but with hyphens
        $string = preg_replace('/[^\w]+/', '-', $string);
        $string = preg_replace('/([a-z])([A-Z])/', '$1-$2', $string);

        return strtolower(trim($string, '-'));
    }

    /**
     * Convert string to Title Case
     */
    public static function toTitleCase(string $string): string
    {
        return ucwords(strtolower($string));
    }

    /**
     * Convert string to UPPER_SNAKE_CASE
     */
    public static function toUpperSnakeCase(string $string): string
    {
        return strtoupper(self::toSnakeCase($string));
    }

    /**
     * Convert string to dot.case
     */
    public static function toDotCase(string $string): string
    {
        $string = preg_replace('/[^\w]+/', '.', $string);
        $string = preg_replace('/([a-z])([A-Z])/', '$1.$2', $string);

        return strtolower(trim($string, '.'));
    }
}
