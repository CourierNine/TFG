import sys
from apify_client import ApifyClient


# Initialize the ApifyClient with your Apify API token

client = ApifyClient("apify_api_aPIQkKHOv1UZeVM1Nh4aGxlbnRzsT40VBJfM")
hashtag = "TFGMicro"

# Prepare the Actor input

run_input = {

    "hashtags": [hashtag],

    "resultsLimit": 3,

}


# Run the Actor and wait for it to finish

run = client.actor("apify/instagram-hashtag-scraper").call(run_input=run_input)


# Fetch and print Actor results from the run's dataset (if there are any)

for item in client.dataset(run["defaultDatasetId"]).iterate_items():
    print(item["displayUrl"], "~~~~", item["ownerUsername"], "~~~~", item["caption"], "\\\\")
