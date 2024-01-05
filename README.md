# Discord Bot and React App Project

This project combines a Discord bot and a React app to gather information about Discord servers and members.

## Discord Bot

The Discord bot retrieves server information and member details using the following commands:

- **$create**: Generates a unique link for the server. Users can invite the bot to their server and use this command to get a link specific to their server.

### Bot Permissions

Make sure the bot has the following permissions:

- Read Messages/View Channels
- View Server Insights
- Send Messages

### Getting Started

1. Obtain a Discord bot token and place it in a file named `.env` in the `api-server` directory:

```bash
TOKEN=your_discord_bot_token_here
```

2. Install dependencies by running:

```bash
cd api-server
npm install
```

3. Start the API server:

```bash
npm start
```

## React App
The React app is responsible for displaying server information.

#### Build and Deployment
Build the React app:

```bash
npm run build
```
Deploy the app as needed.

#### Demo
Visit https://stepbros.ir to see a demo.

To add the bot to your server, use the following link: [Add Bot to Server](https://discord.com/api/oauth2/authorize?client_id=1191658680018546698&permissions=2199023782912&scope=bot). After adding the bot, send the command `$create` in your server to get a unique link for your server, e.g., https://stepbros.ir?id=your_server_id_here.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

<br /><br />

🤝 Feel free to contribute, report issues, or make suggestions! Your input is highly valued.

🌟 If you find this project helpful, please consider giving it a star.