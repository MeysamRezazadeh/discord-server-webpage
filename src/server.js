const express = require('express');
const cors = require('cors');
const app = express();

const { Client, GatewayIntentBits } = require('discord.js');

const client = new Client({
  intents: [
    GatewayIntentBits.Guilds,
    GatewayIntentBits.GuildMembers,
    GatewayIntentBits.GuildPresences
  ]
});

app.use(cors());

client.login('');

app.get('/api/guild', async (req, res) => {
  try {
    const guild = await client.guilds.fetch('');

    // Server icon
    const icon = await guild.iconURL();


    await guild.members.fetch(); // Fetch all members to cache

    const memberCount = guild.memberCount;
    const onlineMembersCount = guild.members.cache.filter(member => member.presence?.status === 'online').size;
    const membersInVoiceChannelCount = guild.members.cache.filter(member => member.voice.channel).size;

    // Get the server creation date
    const createdDate = new Date(guild.createdTimestamp);
    const creationDateString = createdDate.toISOString().replace('T', ', ').replace('Z', '').split('.')[0];

    // Calculate server age in days, hours, minutes, and seconds
    const serverAgeMs = Date.now() - guild.createdTimestamp;
    const serverAgeDays = Math.floor(serverAgeMs / (1000 * 60 * 60 * 24));
    const serverAgeHours = Math.floor((serverAgeMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const serverAgeMinutes = Math.floor((serverAgeMs % (1000 * 60 * 60)) / (1000 * 60));
    const serverAgeSeconds = Math.floor((serverAgeMs % (1000 * 60)) / 1000);

    const serverAgeString = `${serverAgeDays} days, ${serverAgeHours} hours, ${serverAgeMinutes} minutes, ${serverAgeSeconds} seconds`;

    // Emojis
    const emojis = (await guild.emojis.fetch()).map(emoji => ({
      id: emoji.id,
      url: emoji.url + '?size=48&quality=lossless',
      name: emoji.name
    }));


    // Voice Channels
    const voiceChannels = (await guild.channels.fetch()).filter(channel => channel.type == 2);

    const guildInfo = {
      name: guild.name,
      icon: icon,
      description: guild.description,
      memberCount: memberCount,
      onlineMembersCount: onlineMembersCount,
      membersInVoiceChannelCount: membersInVoiceChannelCount,
      emojis: emojis,
      creationDate: creationDateString,
      serverAge: serverAgeString,
      voiceChannels: voiceChannels,
    };

    res.json(guildInfo);
  } catch (error) {
    console.error('Error fetching guild data:', error);
    res.status(500).send('Internal Server Error');
  }
});

// Endpoint to get members names
app.get('/api/members', async (req, res) => {
  try {
    const guild = await client.guilds.fetch('788835011506733108');
    const members = await guild.members.fetch();

    const membersInfo = members.map(member => {
      // Initialize game variables
      let game = null;
      let gameIcon = null;

      // Check presence and activities
      if (member.presence && member.presence.activities) {
        const gameActivity = member.presence.activities.find(activity => activity.type === 0);
        if (gameActivity) {
          game = gameActivity;
          if (game.assets) {
            gameIcon = game.assets.largeImageURL();
          }
        }
      }

      return {
        id: member.user.id,
        name: member.displayName,
        avatar: member.user.displayAvatarURL({ dynamic: true }),
        bot: member.user.bot,
        status: member.presence ? member.presence.status : 'offline',
        game: game ? game : null,
        gameName: game ? game.name : null,
        gameIcon: gameIcon,
        channelId: member.voice.channelId,
        serverDeaf: member.voice.serverDeaf, 
        serverMute: member.voice.serverMute,
        selfDeaf: member.voice.selfDeaf,
        selfMute: member.voice.selfMute,
        selfVideo: member.voice.selfVideo,
        streaming: member.voice.streaming,
        suppress: member.voice.suppress
      };
    });

    res.json(membersInfo);
  } catch (error) {
    console.error('Error fetching members:', error);
    res.status(500).send('Internal Server Error');
  }
});

app.get('/api/member/:id', async (req, res) => {
  try {
    const memberId = req.params.id;

    const guild = await client.guilds.fetch('788835011506733108');
    const member = await guild.members.fetch(memberId);

    if (!member) {
      return res.status(404).json({ error: 'Member not found' });
    }

    let game = null;
    let gameIcon = null;

    if (member.presence && member.presence.activities) {
      const gameActivity = member.presence.activities.find(activity => activity.type === 0);
      if (gameActivity) {
        game = gameActivity;
        if (game.assets) {
          gameIcon = game.assets.largeImageURL();
        }
      }
    }

    const createdDateTimestamp = new Date(member.user.createdAt);
    const joinedDateTimestamp = new Date(member.joinedAt);

    // Get the user creation date
    const createdDate = createdDateTimestamp;
    const creationDateString = createdDate.toISOString().replace('T', ', ').replace('Z', '').split('.')[0];

    // Get the user joined date
    const joinedDate = joinedDateTimestamp;
    const joinedDateString = joinedDate.toISOString().replace('T', ', ').replace('Z', '').split('.')[0];

    // Calculate account age in days, hours, minutes, and seconds
    const accountAgeMs = Date.now() - createdDateTimestamp
    const accountAgeDays = Math.floor(accountAgeMs / (1000 * 60 * 60 * 24));
    const accountAgeHours = Math.floor((accountAgeMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const accountAgeMinutes = Math.floor((accountAgeMs % (1000 * 60 * 60)) / (1000 * 60));
    const accountAgeSeconds = Math.floor((accountAgeMs % (1000 * 60)) / 1000);
    const accountAgeString = `${accountAgeDays} days, ${accountAgeHours} hours, ${accountAgeMinutes} minutes, ${accountAgeSeconds} seconds`;

    const timeInServerMs = Date.now() - joinedDateTimestamp;
    const timeInServerDays = Math.floor(timeInServerMs / (1000 * 60 * 60 * 24));
    const timeInServerHours = Math.floor((timeInServerMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const timeInServerMinutes = Math.floor((timeInServerMs % (1000 * 60 * 60)) / (1000 * 60));
    const timeInServerSeconds = Math.floor((timeInServerMs % (1000 * 60)) / 1000);
    const timeInServerString = `${timeInServerDays} days, ${timeInServerHours} hours, ${timeInServerMinutes} minutes, ${timeInServerSeconds} seconds`;

    // Get color information for each role
    const rolesWithColor = member.roles.cache.map(role => {
      return {
        name: role.name,
        color: role.color.toString(16),
      };
    });
  
    const memberInfo = {
      id: member.user.id,
      name: member.displayName,
      avatar: member.user.displayAvatarURL({ dynamic: true }),
      bot: member.user.bot,
      status: member.presence ? member.presence.status : 'offline',
      game: member.presence ? game : null,
      gameIcon: gameIcon,
      username: member.user.username,
      roles: rolesWithColor,
      creationDate: creationDateString,
      accountAge: accountAgeString,
      joinedDate: joinedDateString,
      timeInServer: timeInServerString,
    };

    res.json(memberInfo);
  } catch (error) {
    console.error('Error fetching member:', error);
    res.status(500).send('Internal Server Error');
  }
});



// Listen on a specific port, e.g., 3001
app.listen(3001, () => {
  console.log('Server started on port 3001');
});