[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Screenshotlayer/functions?utm_source=RapidAPIGitHub_ScreenshotlayerFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# Screenshotlayer Package
Screenshotlayer
* Domain: screenshotlayer.com
* Credentials: apiKey

## How to get credentials: 
0. Go to [Screenshotlayer website](https://screenshotlayer.com) 
1. Log in or create a new account
2. Go to [Dashboard page](https://screenshotlayer.com/dashboard) to get your API key
## Screenshotlayer.captureSnapshot
Verify the provided address

| Field           | Type       | Description
|-----------------|------------|----------
| apiKey          | credentials| Api key obtained from Screenshotlayer
| url             | String     | Website url to capture
| fullPage        | Number     | By default, screenshots are rendered based on the height of the selected (or default) viewport. Alternatively, you can request the full height of the target website to be captured, simply by setting the API's fullPage parameter to 1.
| thumbnailsWidth | Number     | By default, the screenshotlayer API returns your target website's snapshot in original size (1:1). If you'd like to request a thumbnail, append the API's thumbnailsWidth parameter containing your preferred thumbnail width in pixels.
| viewportControl | String     | The screenshotlayer API's default viewportControl setting is 1440x900. You can specify a custom viewport by setting the viewportControl parameter to your desired dimensions. (format: width x height, in pixels)
| outputFormat    | String     | Your snapshots can be requested in three different formats: PNG, JPG and GIF. You can change the default format (PNG) simply by appending the API's outputFormat parameter containing your preferred format
| secretKeyword   | String     | Secret keyword to prevent your publicly displayed API request URL from being abused
| cssUrl          | String     | Inject a custom CSS stylesheet into the target website
| captureDelay    | Number     | Specify a custom delay time (in seconds) before the snapshot is captured
| cachingTime     | Number     | By default, website screenshots are cached for 30 days (2,592,000 seconds). Using the API's cachingTime parameter, you can specify a custom caching time (time-to-live) lower than the default setting.
| forceRefresh    | Number     | You can easily force the API to capture a fresh screenshot of the requested target URL by appending the forceRefresh parameter to the request URL and setting it to 1.
| placeholderImage| String     | By appending the API's placeholderImage parameter and setting it to 1, you can request the default screenshotlayer placeholder image. If you prefer setting your own custom placeholder image, simply append it to the API's placeholderImage parameter as an image URL.Supported file formats: PNG, JPEG, GIF
| userAgent       | String     | By default, the screenshotlayer API does not send any HTTP User-Agent headers with your request. You can specify a custom user-agent string by appending it to the API's userAgent parameter.
| acceptLanguage  | String     | The default HTTP Accept-Language header is en-US, en (US English, or English in general). You can specify a custom Accept-Language header by appending it to the API's acceptLanguage parameter.
| exportTo        | String     | If you are subscribed to the Professional or Enterprise Plan, you may request the API to directly export your snapshot to your AWS S3 Bucket. This can be done simply by appending your S3 Bucket path (format: s3://API_KEY:API_SECRET@bucket) to the API's exportTo parameter. Professional and Enterprise Customers may also specify a custom ftp path to directly export captured snapshots to. This can be achieved simply by appending your desired FTP path (format: ftp://user:password@server) to the API's exportTo parameter.

