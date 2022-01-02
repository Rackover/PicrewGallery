const https = require('https');
const fs = require('fs');

module.exports = {
	execute: async function(client, interaction)
	{
		const channel = interaction.channel;
		
		const messages = await channel.messages.fetch({ limit: 100 })
		
		let processed = 0;
		
		await interaction.reply("OK, processing...");
		
		for (const [_1, message] of messages)
		{
			if (message.attachments.size <= 0) continue; // No attached Image
			
			console.log("Working on message "+message.id+"...");
			
			for (const [_2, attachment] of message.attachments){
				
				if (attachment.name && attachment.name.endsWith(".png"))
				{
					// It's a valid image
					
					if (attachment.name.indexOf("-") > -1)
					{
						// Already valid maybe
						const parts = attachment.name.split("-");
						
						if (parts.length == 2){
							const id = parseInt(parts[0]);
							const initial = parts[1][0];
							
							console.log("Processing well-formed image "+id+":"+initial+" (from attachment name "+attachment.name+")");
							
							if (!isNaN(id)){
								// All good
								await processImage(id, initial, attachment);			
								processed++;
								break;								
							}					
						}
					}
					else
					{
						const reducedName = attachment.name.substring(0, attachment.name.length - 4);
						const id = parseInt(reducedName.split('_')[0]);
						
						if (!isNaN(id) && message.content.length > 0)
						{
							const initial = message.content[0].toUpperCase();
							
							console.log("Processing composite image "+id+":"+initial+" (from attachment name "+attachment.name+")");
							await processImage(id, initial, attachment);			
							processed++;							
							break;
						}
					}
					
				}
				
			}
		}
		
		await interaction.channel.send("Processed "+processed+" images successfully");
	}
}


async function processImage(id, initial, attachment)
{
	const folder = "../faces";
	
	const exists = false;
	
	if (exists)
	{
		
	}
	else
	{
		try{
			const response = await doRequest(attachment.url);
			const file = fs.createWriteStream("../faces/"+id+"-"+initial+".png");
			response.pipe(file);
			
			console.log("Downloaded file "+id+" initial "+initial+"");
		}
		catch (e){
			console.log(e);
		}
	}
}

function doRequest(url) {
  return new Promise ((resolve, reject) => {
			
	console.log("Fetching "+url);
	
    let req = https.get(url, function(res){ resolve(res); });

    req.on('error', err => {
		console.log("Error! "+err);
		reject(err);
    });
  }); 
}