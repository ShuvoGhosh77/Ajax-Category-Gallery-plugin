# Ajax Category Gallery Plugin  

**Ajax Category Gallery Plugin** is a custom WordPress plugin that allows users to upload gallery images as a featured image categorized by custom post type categories. It includes a **popup image link meta box**, enabling users to add a specific link to each image. The gallery is displayed dynamically using a shortcode, with category-based filtering powered by AJAX. When users click an image, it opens in a popup, showing the linked image.  

## 🚀 Features  

- ✅ **Custom Gallery Post Type** – Manage gallery images separately.  
- ✅ **Category-Based Set featured image** –Set featured image  based on categories.  
- ✅ **Popup Image Link Meta Box** – Assign a custom popup image link to each gallery item.  
- ✅ **AJAX-Powered Filtering** – Display gallery images dynamically by category without page reload.  
- ✅ **Shortcode Support** – Easily insert the gallery anywhere using a simple shortcode.  
- ✅ **Popup Image Display** – Clicking an image opens the linked image in a popup.  

## 📌 Installation  

1. **Download or Clone the Plugin:**  
   - Clone this repository or download the ZIP file.  

2. **Upload to WordPress:**  
   - Go to `WordPress Admin > Plugins > Add New`.  
   - Click `Upload Plugin` and select the ZIP file.  
   - Click `Install Now` and then `Activate`.  

3. **Usage:**  
   - Navigate to **Gallery** in the admin dashboard.  
   - Add a **new gallery image**, select a category, and set  a **popup image link**.  
   - Use the shortcode below to display the gallery anywhere:  
     ```sh
     [cps_gallery]
     ```
   - The gallery will display images based on categories with AJAX filtering.  

## ⚙️ How It Works  

1. Users upload images as a featured image via the **Gallery** post type and assign categories.  
2. Each image has a **popup image link** for external or full-size previews.  
3. The shortcode `[cps_gallery]` dynamically displays gallery images with category-based AJAX filtering.  
4. Clicking an image opens a popup displaying the **popup image link image**.
## 🛠️ Shortcode Usage  

- Simply insert `[cps_gallery]` into any page, post, or widget to display the gallery.  
