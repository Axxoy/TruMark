import joblib
import pandas as pd
import distance_method
import sys
import warnings
import os
from sklearn.exceptions import InconsistentVersionWarning

def classify(filename):
    warnings.filterwarnings("ignore", category=InconsistentVersionWarning)

    DIRECTORY = os.path.expanduser('~/server/')
    FILE_NAME = os.path.join(DIRECTORY, 'trained_model_random_forest.pkl')

    # Ensure the directory exists
    if not os.path.exists(DIRECTORY):
        os.makedirs(DIRECTORY)

    # 1. Load the saved model
    loaded_clf = joblib.load(FILE_NAME)

    # 2. Preprocess the input line
    # Sample line from the CSV without the label
    csv_line = ",".join(distance_method.calculate_distance(filename).split(",")[:-1])
    data_instance = pd.DataFrame([csv_line.split(",")], dtype=float)

    # 3. Predict the label for that instance
    prediction = loaded_clf.predict(data_instance)

    # 4. Print the prediction
    return(str(prediction[0]))

if __name__ == "__main__":
    print(classify(sys.argv[1]))
