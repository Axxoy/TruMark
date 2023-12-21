import pandas as pd
from sklearn.neural_network import MLPClassifier
# from sklearn.ensemble import RandomForestClassifier
import joblib
import sys

# Load dataset
data = pd.read_csv(sys.argv[1])

# Assuming the last column is the target variable and the rest are features
X = data.iloc[:, :-1].values
y = data.iloc[:, -1].values

# Create an MLP classifier
# clf = RandomForestClassifier()
clf = MLPClassifier(hidden_layer_sizes=(100,), activation='relu', solver='adam', alpha=0.0001, 
                    batch_size='auto', learning_rate='constant', learning_rate_init=0.001, 
                    max_iter=200, shuffle=True, random_state=None)

# Train the model on the entire dataset
clf.fit(X, y)

# Save the trained model with the '.pkl' extension
joblib.dump(clf, 'trained_model.pkl')
