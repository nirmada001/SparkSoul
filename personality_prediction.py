import mysql.connector
import pandas as pd
from sklearn.cluster import KMeans

# Connect to the database
connection = mysql.connector.connect(host='localhost', user='root', password='', database='socialnetwork')

# Retrieve data from the database
query = "SELECT * FROM personality_test"
df = pd.read_sql(query, connection)

# Close the database connection
connection.close()


kmeans = KMeans(n_clusters=5) #number of clusters
kmeans.fit(df)

# Predict cluster label for the last record
last_record = df.iloc[[-1]]  # Select the last row of the dataframe
cluster_label = kmeans.predict(last_record)[0]

# Map cluster label to personality type
personality_types = {
    0: "Group_A",
    1: "Group_B",
    2: "Group_C",
    3: "Group_D",
    4: "Group_E",
}

# Get predicted personality type for the last record
predicted_personality_type = personality_types[cluster_label]

# Print predicted personality type for the last record
print(f"{predicted_personality_type}")

