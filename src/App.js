// App.js (This is a very simplified version)

import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Modal from 'react-modal';
import botImage from './images/bot.png';
import onlineImage from './images/online.png';
import offlineImage from './images/offline.png';
import idleImage from './images/idle.png';
import dndImage from './images/dnd.png';
import speakerImage from './images/speaker.svg';
import MemberPopup from './components/MemberPopup';
import Popup from 'reactjs-popup';
import 'reactjs-popup/dist/index.css';

const App = () => {
  const [members, setMembers] = useState([]);
  const [guild, setGuild] = useState([]);

  useEffect(() => {
    axios.get('http://localhost:3001/api/members')
      .then(response => {
        setMembers(response.data);
        console.log(response.data);
      }).catch(error => {
        console.error("Error fetching members:", error);
      });

    axios.get('http://localhost:3001/api/guild')
      .then(response => {
        setGuild(response.data);
        console.log(response.data);
      }).catch(error => {
        console.error("Error fetching members:", error);
      });
  }, []);

  const getStatusColor = (status) => {
    switch (status) {
      case 'online':
        return '2px solid green';
      case 'idle':
        return '2px solid yellow';
      case 'dnd':
        return '2px solid red';
      case 'offline':
        return '2px solid grey';
      default:
        return '2px solid grey'; // Default to grey for unknown status
    }
  };
  const getStatusImage = (status) => {
    switch (status) {
      case 'online':
        return onlineImage;
      case 'idle':
        return idleImage;
      case 'dnd':
        return dndImage;
      default:
        return offlineImage;
    }
  };

  return (
    <div className='container'>
      <div class="row d-flex">
        <div class="server">
          <img src={guild.icon} alt="" class="logo" />

          <h1>{guild.name}</h1>
          <hr></hr>
          <table class="table-resp">

            <tbody>
              <tr>
                <td>All Members:</td>
                <td>{guild.memberCount}</td>
              </tr>
              <tr>
                <td>Online Members:</td>
                <td>{guild.onlineMembersCount}</td>
              </tr>
              <tr>
                <td>Members In Vc:</td>
                <td>{guild.membersInVoiceChannelCount}</td>
              </tr>
              <tr>
                <td>Creation Date:</td>
                <td>{guild.creationDate}</td>
              </tr>
              <tr>
                <td>Server Age:</td>
                <td>{guild.serverAge}</td>
              </tr>
            </tbody>
          </table>

          <div>
            <a href="">Join Server</a>
          </div>
        </div>

        <div class="emojis">
          <span>EMOJIS</span>
          <hr></hr>
          {guild && guild.emojis && guild.emojis.map(emoji => (
            <img key={emoji.id} src={emoji.url} alt={`Emoji ${emoji.name}`} />
          ))}


        </div>
      </div>
      <div class="row d-block">
        <span>VOICE CHANNELS</span>
        <hr></hr>
      </div>

      <div class="row d-flex">
        <div class="channels d-flex">
          {guild && guild.voiceChannels && guild.voiceChannels.map(voiceChannel => (
            <div className="channel" key={voiceChannel.id}>
              <div className="d-flex">
                <img src={speakerImage} alt="" />
                <div className="channel-title">
                  <span>{voiceChannel.name}</span>
                </div>

              </div>
              <div className="channel-members">
                {members.filter(member => member.channelId === voiceChannel.id).map(memberInVc => (
                  <div className="card-small d-flex" style={{ backgroundImage: `url(${memberInVc.avatar})` }}>
                    <Popup trigger={
                      <button key={memberInVc.id} id="btn" style={{ padding: 0 }}>
                        <div className="status" style={{
                          borderLeft: getStatusColor(memberInVc.status)
                        }}></div>
                        <div class="card-small-title d-flex">
                          <div style={{ margin: 'auto 5px auto auto' }}>
                            <span>{memberInVc.name}</span>
                          </div>
                          {memberInVc.bot ? (
                            <img src={botImage} alt="" />
                          ) : (
                            null // You can use null or an empty string here
                          )}
                        </div>

                        <div className="mute-deaf">
                          {/* MUTE */}
                          {memberInVc.serverMute ? (
                            <svg class="mute-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clip-rule="evenodd" class=""></path><path fill="currentColor" d="M12 2c.33 0 .51.35.4.66a6.99 6.99 0 0 0 3.04 8.37c.2.12.31.37.21.6A4 4 0 0 1 8 10V6a4 4 0 0 1 4-4Z" class=""></path><path fill="currentColor" d="M17.55 12.29c.1-.23.33-.37.58-.34.29.03.58.05.87.05h.04c.35 0 .63.32.51.65A8 8 0 0 1 13 17.94V20h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2v-2.06A8 8 0 0 1 4 10a1 1 0 0 1 2 0 6 6 0 0 0 11.55 2.29Z" class=""></path></svg>
                          ) : memberInVc.selfMute ? (
                            <svg class="self-mute-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clip-rule="evenodd" class=""></path><path fill="currentColor" d="M12 2c.33 0 .51.35.4.66a6.99 6.99 0 0 0 3.04 8.37c.2.12.31.37.21.6A4 4 0 0 1 8 10V6a4 4 0 0 1 4-4Z" class=""></path><path fill="currentColor" d="M17.55 12.29c.1-.23.33-.37.58-.34.29.03.58.05.87.05h.04c.35 0 .63.32.51.65A8 8 0 0 1 13 17.94V20h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2v-2.06A8 8 0 0 1 4 10a1 1 0 0 1 2 0 6 6 0 0 0 11.55 2.29Z" class=""></path></svg>
                          ) : null}

                          {/* DEAF */}
                          {memberInVc.serverDeaf ? (
                            <svg class="deaf-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clip-rule="evenodd" class=""></path><path fill="currentColor" d="M12.38 1c.38.02.58.45.4.78-.15.3-.3.62-.4.95A.4.4 0 0 1 12 3a9 9 0 0 0-8.95 10h1.87a5 5 0 0 1 4.1 2.13l1.37 1.97a3.1 3.1 0 0 1-.17 3.78 2.85 2.85 0 0 1-3.55.74 11 11 0 0 1 5.71-20.61ZM22.22 11.22c.34-.18.76.02.77.4L23 12a11 11 0 0 1-5.67 9.62c-1.27.71-2.73.23-3.55-.74a3.1 3.1 0 0 1-.17-3.78l1.38-1.97a5 5 0 0 1 4.1-2.13h1.86c.03-.33.05-.66.05-1a.4.4 0 0 1 .27-.38c.33-.1.65-.25.95-.4Z" class=""></path></svg>
                          ) : memberInVc.selfDeaf ? (
                            <svg class="self-deaf-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clip-rule="evenodd" class=""></path><path fill="currentColor" d="M12.38 1c.38.02.58.45.4.78-.15.3-.3.62-.4.95A.4.4 0 0 1 12 3a9 9 0 0 0-8.95 10h1.87a5 5 0 0 1 4.1 2.13l1.37 1.97a3.1 3.1 0 0 1-.17 3.78 2.85 2.85 0 0 1-3.55.74 11 11 0 0 1 5.71-20.61ZM22.22 11.22c.34-.18.76.02.77.4L23 12a11 11 0 0 1-5.67 9.62c-1.27.71-2.73.23-3.55-.74a3.1 3.1 0 0 1-.17-3.78l1.38-1.97a5 5 0 0 1 4.1-2.13h1.86c.03-.33.05-.66.05-1a.4.4 0 0 1 .27-.38c.33-.1.65-.25.95-.4Z" class=""></path></svg>
                          ) : null}
                        </div>
                      </button>} position="right center">
                      {close => <MemberPopup memberId={memberInVc.id} onClose={close} />}
                    </Popup>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>

      <div class="row d-block">
        <span>ONLINE MEMBERS ({members.filter(member => member.status !== 'offline').length})</span>
      </div>
      <div class="row d-flex">
        {members.filter(member => member.status !== 'offline').map(member => (
          <div className='card d-flex'>
            <Popup trigger={
              <button id="btn">
                <div>
                  <img className="img-profile" src={member.avatar} alt={member.name} />
                  <img class="img-status" src={getStatusImage(member.status)} alt={member.status}></img>
                </div>
                <div className="card-center d-flex">
                  <div style={{ margin: 'auto 5px auto auto' }}>
                    <span>{member.name}</span>
                {member.game && !member.bot ?
                    <p style={{ fontSize: '12px', margin: '0' }}>Playing: {member.gameName}</p> : ""}
                  </div>
                  {member.bot ? <img src={botImage} alt="" style={{ width: '32px' }} /> : ""}
                </div>

                {member.game && !member.bot && member.gameIcon ?
                  <div class="card-right">
                    <img src={member.gameIcon} alt="" />
                  </div> : ""}
              </button>} position="right center">
              {close => <MemberPopup memberId={member.id} onClose={close} />}
            </Popup>
          </div>
        ))}
      </div>

      <div class="row d-block">
        <span>OFFLINE MEMBERS ({members.filter(member => member.status === 'offline').length})</span>
      </div>
      <div class="row d-flex">
        {members.filter(member => member.status === 'offline').map(member => (
          <div className='card d-flex'>
            <Popup trigger={
              <button id="btn">
                <div>
                  <img className="img-profile" src={member.avatar} alt={member.name} />
                </div>
                <div className="card-center d-flex">
                  <div style={{ margin: 'auto 5px auto auto' }}>
                    <span>{member.name}</span>
                  </div>
                  {member.bot ? <img src={botImage} alt="" style={{ width: '32px' }} /> : ""}
                </div>

              </button>} position="right center">
              {close => <MemberPopup memberId={member.id} onClose={close} />}
            </Popup>
          </div>
        ))}
      </div>

      <div class="footer d-grid">
        <a href="https://github.com/MeysamRezazadeh/Discord-Widget">
          Download source code
        </a>
        <span>Create by <a href="https://github.com/MeysamRezazadeh" target="_blank">Sambyte</a></span>
      </div>
    </div>
  );
};

export default App;