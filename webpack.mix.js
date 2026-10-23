const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.js("resources/js/app.js", "public/dist/js")
    .js("resources/js/ckeditor-classic.js", "public/dist/js")
    .js("resources/js/ckeditor-inline.js", "public/dist/js")
    .js("resources/js/ckeditor-balloon.js", "public/dist/js")
    .js("resources/js/ckeditor-balloon-block.js", "public/dist/js")
    .js("resources/js/ckeditor-document.js", "public/dist/js")
    // ⬇️ TAMBAH INI — CKEditor Custom Build (Image Upload + Caption)
    .js("resources/js/ckeditor-build/entry.js", "public/dist/js/ckeditor-custom.js")
    .postCss("resources/css/app.css", "public/dist/css/app.css", [
        require("postcss-import"),
        require("tailwindcss"),
    ])
    .options({
        processCssUrls: false,
    })
    .copyDirectory("resources/json", "public/dist/json")
    .copyDirectory("resources/fonts", "public/dist/fonts")
    .copyDirectory("resources/images", "public/dist/images")
    .browserSync({
        proxy: "spa.test",
        files: ["resources/**/*.*"],
    });