import React, { useState, useEffect } from 'react';
import axios from 'axios';
import botImage from './images/bot.png';
import onlineImage from './images/online.png';
import offlineImage from './images/offline.png';
import idleImage from './images/idle.png';
import dndImage from './images/dnd.png';
import speakerImage from './images/speaker.svg';
import MemberPopup from './components/MemberPopup';
import Popup from 'reactjs-popup';
import 'reactjs-popup/dist/index.css';
import Switch from "react-switch";

const App = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [guild, setGuild] = useState([]);
  const [members, setMembers] = useState([]);
  const [voiceChannels, setVoiceChannels] = useState([]);

  let serverId;

  useEffect(() => {
    const fetchData = async () => {
      try {
        const url = new URL(window.location.href);
        const urlSearchParams = new URLSearchParams(url.search);
        const id = urlSearchParams.get('id') ?? '788835011506733108';
        serverId = id;
  
        console.log('id:', id);
  
        const guildResponse = await axios.get(`https://stepbros.ir/api/${id}`);
  
        setGuild(guildResponse.data);
        setMembers(guildResponse.data.members);
        setVoiceChannels(guildResponse.data.voiceChannels);
        console.log('Guild:', guildResponse.data);
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        setIsLoading(false);
      }
    };
  
    fetchData();
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
        return '2px solid grey';
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

  const [starsVisible, setStarsVisible] = useState(true);

  const toggleStarsVisibility = () => {
    setStarsVisible(!starsVisible);
  };

  return (
    <div>
      {isLoading && (<section>
      <svg>
        <filter id="gooey">
          <feGaussianBlur in="SourceGraphic" stdDeviation="10" />
          <feColorMatrix
            values="1 0 0 0 0
                    0 1 0 0 0
                    0 0 1 0 0
                    0 0 0 20 -10"
          />
        </filter>
      </svg>
      <div className="loader">
        {[...Array(8)].map((_, index) => (
          <span key={index} style={{ '--i': index + 1 }}></span>
        ))}
        {[...Array(5)].map((_, index) => (
          <span key={index} className="rotate" style={{ '--j': index }}></span>
        ))}
      </div>
    </section>)}

    {starsVisible && (
        <div>
          <div id='stars'></div>
          <div id='stars2'></div>
          <div id='stars3'></div>
        </div>
      )}
      {!isLoading && (
      
    
  
      <div className='container'>
        <Switch onChange={toggleStarsVisibility} checked={starsVisible} />
        <div className="row d-flex">
          <div className="server">
            <img src={guild.icon} alt="" className="logo" />

            <h1>{guild.name}</h1>
            <hr></hr>
            <table className="table-resp">

              <tbody>
                <tr>
                  <td>All Members:</td>
                  <td>{guild.memberCount}</td>
                </tr>
                <tr>
                  <td>Online Members: </td>
                  <td>{members.filter(member => member.status !== 'offline').length}</td>
                </tr>
                <tr>
                  <td>Members In Vc: </td>
                  <td>{voiceChannels.reduce((total, voiceChannel) => total + voiceChannel.members.length, 0)}</td>
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

            {serverId === '788835011506733108' && (<div>
              <a href='https://discord.gg/KTSms7k2'>Join Server</a>
            </div>)}
          </div>

          <div className="emojis">
            <span>EMOJIS</span>
            <hr></hr>
            {guild && guild.emojis && guild.emojis.map(emoji => (
              <img key={emoji.id} src={emoji.url} alt={`Emoji ${emoji.name}`} />
            ))}


          </div>
        </div>
        <div className="row d-block">
          <span>VOICE CHANNELS</span>
          <hr></hr>
        </div>

        <div className="row d-flex">
          <div className="channels d-flex">
            {voiceChannels && voiceChannels.map(voiceChannel => (
              <div className="channel" key={voiceChannel.id}>
                <div className="d-flex">
                  <img src={speakerImage} alt="" />
                  <div className="channel-title">
                    <span>{voiceChannel.name}</span>
                  </div>

                </div>
                <div className="channel-members">
                  {voiceChannel.members.map(memberInVc => (
                    <div className="card-small d-flex" style={{ backgroundImage: `url(${memberInVc.avatar})` }}>
                      <Popup trigger={
                        <button key={memberInVc.id} id="btn" style={{ padding: 0 }}>
                          <div className="status" style={{
                            borderLeft: getStatusColor(memberInVc.status)
                          }}></div>
                          <div className="card-small-title d-flex">
                            <div style={{ margin: 'auto 5px auto auto' }}>
                              <span>{memberInVc.name}</span>
                            </div>
                            {memberInVc.bot ? (
                              <img src={botImage} alt="" />
                            ) : (
                              null
                            )}
                          </div>

                          <div className="mute-deaf">
                            {/* MUTE */}
                            {memberInVc.serverMute ? (
                              <svg className="mute-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clipRule="evenodd" className=""></path><path fill="currentColor" d="M12 2c.33 0 .51.35.4.66a6.99 6.99 0 0 0 3.04 8.37c.2.12.31.37.21.6A4 4 0 0 1 8 10V6a4 4 0 0 1 4-4Z" className=""></path><path fill="currentColor" d="M17.55 12.29c.1-.23.33-.37.58-.34.29.03.58.05.87.05h.04c.35 0 .63.32.51.65A8 8 0 0 1 13 17.94V20h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2v-2.06A8 8 0 0 1 4 10a1 1 0 0 1 2 0 6 6 0 0 0 11.55 2.29Z" className=""></path></svg>
                            ) : memberInVc.selfMute ? (
                              <svg className="self-mute-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clipRule="evenodd" className=""></path><path fill="currentColor" d="M12 2c.33 0 .51.35.4.66a6.99 6.99 0 0 0 3.04 8.37c.2.12.31.37.21.6A4 4 0 0 1 8 10V6a4 4 0 0 1 4-4Z" className=""></path><path fill="currentColor" d="M17.55 12.29c.1-.23.33-.37.58-.34.29.03.58.05.87.05h.04c.35 0 .63.32.51.65A8 8 0 0 1 13 17.94V20h2a1 1 0 1 1 0 2H9a1 1 0 1 1 0-2h2v-2.06A8 8 0 0 1 4 10a1 1 0 0 1 2 0 6 6 0 0 0 11.55 2.29Z" className=""></path></svg>
                            ) : null}

                            {/* DEAF */}
                            {memberInVc.serverDeaf ? (
                              <svg className="deaf-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clipRule="evenodd" className=""></path><path fill="currentColor" d="M12.38 1c.38.02.58.45.4.78-.15.3-.3.62-.4.95A.4.4 0 0 1 12 3a9 9 0 0 0-8.95 10h1.87a5 5 0 0 1 4.1 2.13l1.37 1.97a3.1 3.1 0 0 1-.17 3.78 2.85 2.85 0 0 1-3.55.74 11 11 0 0 1 5.71-20.61ZM22.22 11.22c.34-.18.76.02.77.4L23 12a11 11 0 0 1-5.67 9.62c-1.27.71-2.73.23-3.55-.74a3.1 3.1 0 0 1-.17-3.78l1.38-1.97a5 5 0 0 1 4.1-2.13h1.86c.03-.33.05-.66.05-1a.4.4 0 0 1 .27-.38c.33-.1.65-.25.95-.4Z" className=""></path></svg>
                            ) : memberInVc.selfDeaf ? (
                              <svg className="self-deaf-icon" aria-hidden="true" role="img" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fillRule="evenodd" d="M21.76.83a5.02 5.02 0 0 1 .78 7.7 5 5 0 0 1-7.07 0 5.02 5.02 0 0 1 0-7.07 5 5 0 0 1 6.29-.63Zm-4.88 2.05a3 3 0 0 1 3.41-.59l-4 4a3 3 0 0 1 .59-3.41Zm4.83.83-4 4a3 3 0 0 0 4-4Z" clipRule="evenodd" className=""></path><path fill="currentColor" d="M12.38 1c.38.02.58.45.4.78-.15.3-.3.62-.4.95A.4.4 0 0 1 12 3a9 9 0 0 0-8.95 10h1.87a5 5 0 0 1 4.1 2.13l1.37 1.97a3.1 3.1 0 0 1-.17 3.78 2.85 2.85 0 0 1-3.55.74 11 11 0 0 1 5.71-20.61ZM22.22 11.22c.34-.18.76.02.77.4L23 12a11 11 0 0 1-5.67 9.62c-1.27.71-2.73.23-3.55-.74a3.1 3.1 0 0 1-.17-3.78l1.38-1.97a5 5 0 0 1 4.1-2.13h1.86c.03-.33.05-.66.05-1a.4.4 0 0 1 .27-.38c.33-.1.65-.25.95-.4Z" className=""></path></svg>
                            ) : null}
                          </div>
                        </button>} position="right center">
                        {close => <MemberPopup serverId={guild.id} memberId={memberInVc.id} onClose={close} />}
                      </Popup>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>

        <div className="row d-block">
          <span>ONLINE MEMBERS ({members.filter(member => member.status !== 'offline').length})</span>
        </div>
        <div className="row d-flex">
          {members.filter(member => member.status !== 'offline').map(member => (
            <div className='card d-flex'>
              <Popup trigger={
                <button id="btn">
                  <div style={{ position: 'relative' }}>
                    <img className="img-profile" src={member.avatar} alt={member.name} />
                    <img className="img-status" src={getStatusImage(member.status)} alt={member.status}></img>
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
                    <div className="card-right">
                      <img src={member.gameIcon} alt="" />
                    </div> : ""}
                </button>} position="right center">
                {close => <MemberPopup serverId={guild.id} memberId={member.id} onClose={close} />}
              </Popup>
            </div>
          ))}
        </div>

        <div className="row d-block">
          <span>OFFLINE MEMBERS ({members.filter(member => member.status === 'offline').length})</span>
        </div>
        <div className="row d-flex">
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
                {close => <MemberPopup serverId={guild.id} memberId={member.id} onClose={close} />}
              </Popup>
            </div>
          ))}
        </div>

        <div className="footer d-grid">
          <span>Create by <a href="https://github.com/MeysamRezazadeh" target="_blank">Sambyte</a></span>
        </div>
      </div>)}
    </div>
  );
};

export default App;