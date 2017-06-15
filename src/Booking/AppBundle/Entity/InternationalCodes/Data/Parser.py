import json
import sys

if (len(sys.argv) != 3):
    print("ERROR argument Missing (input_file ouput_file)")
else:
    with open(sys.argv[1]) as data_file:
        data = json.load(data_file)

    newArray = {
        "information": data["information"],
        "content": {}
    }
    for iata, icao in data["content"].iteritems():
        newArray["content"][iata] = { "icao" : icao, "iata" : iata }

    newArray["information"]["version"] += ".1"
    newArray["information"]["type"] = "concentrated"
    newArray["information"]["length"] = len(data["content"])

    with open(sys.argv[2], 'wb') as outfile:
        json.dump(newArray, outfile)

print("Quit.")
