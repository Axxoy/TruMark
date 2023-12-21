from PIL import Image
import sys

def trim_image(img):
    # Determine the dimensions for cropping
    width, height = img.size
    pixel_crop_amount = 50
    left = pixel_crop_amount
    top = pixel_crop_amount
    right = width - pixel_crop_amount
    bottom = height - pixel_crop_amount

    # Crop the image
    cropped_img = img.crop((left, top, right, bottom))
    
    return cropped_img

def stretch_array(arr, target_rows, target_cols):
    source_rows = len(arr)
    source_cols = len(arr[0]) if source_rows > 0 else 0

    # Calculate the stretching factors for rows and columns
    row_ratio = source_rows / target_rows
    col_ratio = source_cols / target_cols

    stretched = []

    for i in range(target_rows):
        new_row = []
        for j in range(target_cols):
            # Find the nearest neighbor in the original array
            source_i = int(i * row_ratio)
            source_j = int(j * col_ratio)
            
            # Append the value from the original array to the new row
            new_row.append(arr[source_i][source_j])
        
        stretched.append(new_row)

    return stretched

def remove_rows_and_columns_with_few_ones(two_d_array):
    threshold = 0.01

    # Helper function to check if the row/column should be removed
    def should_remove(sequence):
        total = len(sequence)
        ones_count = sum(cell == 1 for cell in sequence)
        return ones_count / total < threshold

    # Remove rows from the top with less than 5% ones
    while two_d_array and should_remove(two_d_array[0]):
        two_d_array.pop(0)
    
    # Remove rows from the bottom with less than 5% ones
    while two_d_array and should_remove(two_d_array[-1]):
        two_d_array.pop(-1)

    # Transpose the array for column operations
    transposed = list(zip(*two_d_array))

    # Remove columns from the left with less than 5% ones
    while transposed and should_remove(transposed[0]):
        transposed.pop(0)

    # Remove columns from the right with less than 5% ones
    while transposed and should_remove(transposed[-1]):
        transposed.pop(-1)

    # Transpose back to get the final cleaned array
    cleaned_array = [list(row) for row in zip(*transposed)]
    
    return cleaned_array

def png_to_grayscale(filepath):
    # Open the image
    img = Image.open(filepath)
    
    # img = trim_image(img)

    # Convert the image to RGB (if it isn't)
    img_rgb = img.convert('RGB')
    
    # Convert the image to grayscale
    grayscale_img = img_rgb.convert('L')
    
    # Extract pixel data and store in a two-dimensional list
    pixel_data = []
    width, height = grayscale_img.size
    for y in range(height):
        row = []
        for x in range(width):
            gray_value = grayscale_img.getpixel((x, y))
            gray_value = 0 if gray_value >= 200 else 1
            row.append(gray_value)
        pixel_data.append(row)
    
    pixel_data = remove_rows_and_columns_with_few_ones(pixel_data)

    pixel_data = stretch_array(pixel_data, 100, 200)
    
    return pixel_data

def calculate_data_point(pixel_values, label):
    data_points = []
    for i in range(len(pixel_values)):
        sum = 0
        for j in range(len(pixel_values[i])):
            sum += (j+1) * pixel_values[i][j]
        data_points.append(sum)
    data_points.append(label)
    return data_points

def calculate_distance(filepath):
    # Test the function
    label = filepath.split("_")[-2]
    pixel_values = png_to_grayscale(filepath)
    data_point_values = calculate_data_point(pixel_values, label)
    instance = ",".join(map(str, data_point_values))
    return instance

if __name__ == '__main__':
    print(calculate_distance(sys.argv[1]))