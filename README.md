<center>
<img style="max-height:400px" src="https://banners.beyondco.de/pdf-to-ppm.png?theme=dark&packageManager=composer+require&packageName=lukasss93%2Fpdf-to-ppm&pattern=topography&style=style_1&description=PHP+wrapper+for+the+pdftoppm+command+which+is+part+of+poppler-utils&md=1&showWatermark=0&fontSize=125px&images=photograph">
</center>

# Convert a pdf to an image

TODO: badges

PHP wrapper for the [pdftoppm](http://linux.die.net/man/1/pdftoppm) command which is part of [poppler-utils](http://en.wikipedia.org/wiki/Poppler_(software)).

## Requirements

Behind the scenes this package leverages [pdftoppm](http://linux.die.net/man/1/pdftoppm). 
You can verify if the binary installed on your system by issueing this command:

```bash
which pdftoppm
```

If it is installed it will return the path to the binary.

To install the binary you can use this command on Ubuntu or Debian:

```bash
apt-get install poppler-utils
```

On a mac you can install the binary using brew

```bash
brew install poppler
```

If you're on RedHat or CentOS use this:

```bash
yum install poppler-utils
```

Available packages: [http://pkgs.org/download/poppler-utils](http://pkgs.org/download/poppler-utils)

## Installation

You can install the package via composer:

```bash
composer require lukasss93/pdf-to-ppm
```

## Usage

Converting a pdf to an image is easy.

```php
use Lukasss93\PdfToPpm\PdfToPpm;

$pdf = PdfToPpm::create()->setPdf($pathToPdf);
$pdf->saveImage($pathToWhereImageShouldBeStored); // it will save the first page
```

If the path you pass to `saveImage` has the extensions `ppm`, `jpg`, `png` or `tif` the image will be saved in that
format. Otherwise the output will be a jpg.

Converting all pdf pages:

```php
use Lukasss93\PdfToPpm\PdfToPpm;

$pdf = PdfToPpm::create()->setPdf($pathToPdf);
$pdf->saveAllPagesAsImages($pathToWhereImageShouldBeStored);
```

### Other methods

You can get the total number of pages in the pdf:

```php
$pdf->getNumberOfPages(); //returns an int
```

By default the first page of the pdf will be rendered. If you want to render another page you can do so:

```php
$pdf->setPage(2)
    ->saveImage($pathToWhereImageShouldBeStored); // it saves the second page
```

You can override the output format:

```php
$pdf->setOutputFormat('png')
    ->saveImage($pathToWhereImageShouldBeStored); // the output wil be a png, no matter what
```

You can set the resolution (default: 144):

```php
$pdf->setResolution(200); // sets the resolution
```

You can save image to grayscale:

```php
$pdf->setGray(true); // sets the grayscale
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email lucapatera@outlook.it instead of using the issue tracker.

## License

The MIT License (MIT). Please see [LICENSE.md](.github/LICENSE.md) file for more information.