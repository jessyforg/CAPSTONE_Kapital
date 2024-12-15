import sys
import json
from sklearn.metrics.pairwise import cosine_similarity

# Load data from PHP
data = json.loads(sys.argv[1])
investor = data['investor']
startups = data['startups']

# Function to process vectors
def process_vector(data, keys):
    return [data[key] for key in keys]

# Define keys for comparison
keys = ['industry', 'location', 'funding_stage']

# Convert JSON to vectors and calculate scores
matches = []
for startup in startups:
    investor_vector = process_vector(investor, keys)
    startup_vector = process_vector(startup, keys)
    score = cosine_similarity([investor_vector], [startup_vector])[0][0]
    matches.append({'startup_id': startup['startup_id'], 'name': startup['name'], 'score': score})

# Sort and return results
matches.sort(key=lambda x: x['score'], reverse=True)
print(json.dumps(matches))
