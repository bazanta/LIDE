//Initialisation des dépendances
var gulp = require('gulp');
var sass = require('gulp-sass');
var copy = require('gulp-copy');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var livereload = require('gulp-livereload');

    //Compilation et copy des fichiers SASS
    gulp.task('sass', function () {
        gulp.src('./src/MainBundle/Resources/public/scss/**/**.scss')
            .pipe(sass({sourceComments: 'map'}))
            .pipe(sass.sync().on('error', sass.logError))
            .pipe(gulp.dest('./web/css/'))
            .pipe(livereload());
    });

    gulp.task('min', function () {
        gulp.src('./web/css/*.css')
            .pipe(cssmin())
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest('./web/css/'));

    });

    //Copie des JS
    gulp.task('js', function () {
        gulp.src('./src/MainBundle/Resources/public/js/**/**.js')
            .pipe(gulp.dest('./web/js/',{overwrite: true}));
    });

    //Copie de CSS
    gulp.task('css', function () {
        gulp.src('./src/MainBundle/Resources/public/css/**/**.css')
            .pipe(gulp.dest('./web/css/',{overwrite: true}));
    });


    //Déplacement des plugins depuis node_modules(npm)
    gulp.task('js-plugins', function () {
        gulp.src([
            './node_modules/jquery/dist/jquery.min.js',
            './node_modules/bootstrap/dist/js/bootstrap.min.js',
            './node_modules/popper.js/dist/popper.min.js'
        ]).pipe(gulp.dest('./web/js/plugins/',{overwrite: true}));
    });

    //Copie des Images
    gulp.task('img', function () {
        gulp.src('./src/MainBundle/Resources/public/images/**/**')
            .pipe(gulp.dest('./web/images/',{overwrite: true}));
    });

//WATCHER
    gulp.task('watch', function () {
        livereload.listen();

        gulp.watch('./src/MainBundle/Resources/public/scss/**/*.scss', ['sass']);
        gulp.watch('./src/MainBundle/Resources/public/js/**/*.js', ['js']);
        gulp.watch('./src/MainBundle/Resources/views/**/*.html.twig', ['html']);
    });


//Taches par défaults
gulp.task('default', ['css','js']);

gulp.task('all', ['sass','js','img','js-plugins']);

gulp.task('prod', ['sass','js','img','js-plugins','min']);
